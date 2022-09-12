<?php /** @noinspection ALL */


namespace App\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

trait CrudTrait
{
    /**
     *     if your controller inherit this trait, your constructor must be like
     *     public function __construct(SettingRequest $request){
     *     $this->request        = $request;
     *     $this->relations = ['category:id,name'];
     *     $this->model          = new Setting();
     *     $this->fields         = ["key", "group", "value", "type"];
     *    }
     */

    /**
     * num of items in the page
     * @var int
     */
    private $num_of_items = 28;

    /**
     * @var Model
     */
    private $model;

    /**
     * search filed name
     * @var string
     */
    private $searchField = 'name';

    /**
     * model fields
     * @var array
     */
    private $fields = [];

    /**
     * relationships
     * @var array
     */
    private $relations = [];

    /**
     * create new item
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request)
    {
        $request = $this->mergeBeforeStore($request);
        $this->customValidation();
        $data = $this->model->create($request->only($this->fields));
        return Response::successResponse($data);
    }

    /**
     * get all items
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $data = $this->model
            ->with($this->relations)
            ->orderBy('id', 'desc');

        $data = $this->filterData($request, $data);

        if ($request->keyword) {
            $data = $data->where($this->searchField, 'like', '%' . $request->keyword . '%');
        }

        $data = ($request->return_all == 0) ? $data->paginate($this->num_of_items) : $data->get();
        return Response::successResponse($data);
    }

    /**
     * get item details by id
     * @param $id
     * @return mixed
     */
    public function get($id)
    {
        return Response::successResponse(
            $this->model
                ->where('id', $id)
                ->with($this->relations)
                ->firstOrFail()
        );
    }

    /**
     * update item using id
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function update(Request $request, $id)
    {
        $request = $this->mergeBeforeStore($request);
        $this->customValidation();
        $data = $this->model->findOrFail($id);
        $data->update($request->only($this->fields));
        return Response::successResponse($data);
    }

    /**
     * delete item
     * @param $id
     * @return mixed
     */
    public function delete($id)
    {
        $this->customValidation();
        $data = $this->model->findOrFail($id);
        if ($data->delete()) {
            return Response::successResponse(["is_success" => true]);
        }
        return Response::errorResponse([["is_success" => false]]);
    }

    /**
     * merge before store
     * @param $request
     *
     */
    public function mergeBeforeStore($request)
    {
        return $request;
    }

    public function filterData($request, $data)
    {
        return $data;
    }

    public function customValidation()
    {

    }
}

