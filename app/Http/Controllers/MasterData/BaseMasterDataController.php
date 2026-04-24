<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

abstract class BaseMasterDataController extends Controller
{
    abstract protected function modelClass(): string;
    abstract protected function viewPrefix(): string;
    abstract protected function routePrefix(): string;
    abstract protected function title(): string;
    abstract protected function storeRequestClass(): string;
    abstract protected function updateRequestClass(): string;

    protected function query(): Builder
    {
        $modelClass = $this->modelClass();

        return $modelClass::query();
    }

    protected function findModel(int|string $id): Model
    {
        $modelClass = $this->modelClass();

        return $modelClass::query()->findOrFail($id);
    }

    protected function searchableColumns(): array
    {
        $modelClass = $this->modelClass();
        $model = new $modelClass();
        $table = $model->getTable();

        $candidates = ['name', 'code', 'description','email'];

        return array_values(array_filter($candidates, fn (string $column) => Schema::hasColumn($table, $column)));
    }

    protected function items(Request $request): LengthAwarePaginator
    {
        return $this->query()
            ->when($request->filled('q'), function (Builder $query) use ($request) {
                $q = $request->string('q')->toString();
                $columns = $this->searchableColumns();

                if (empty($columns)) {
                    return;
                }

                $query->where(function (Builder $sub) use ($q, $columns) {
                    foreach ($columns as $index => $column) {
                        if ($index === 0) {
                            $sub->where($column, 'like', "%{$q}%");
                        } else {
                            $sub->orWhere($column, 'like', "%{$q}%");
                        }
                    }
                });
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();
    }

    public function index(Request $request)
    {
        return view($this->viewPrefix().'.index', [
            'title' => $this->title(),
            'items' => $this->items($request),
            'routePrefix' => $this->routePrefix(),
            'viewPrefix' => $this->viewPrefix(),
        ]);
    }

    public function create()
    {
        return view($this->viewPrefix().'.create', [
            'title' => $this->title(),
            'routePrefix' => $this->routePrefix(),
            'viewPrefix' => $this->viewPrefix(),
        ]);
    }

    public function store(Request $request)
    {
        $requestClass = $this->storeRequestClass();

        /** @var FormRequest $validatedRequest */
        $validatedRequest = app($requestClass);
        $validated = $validatedRequest->validated();

        $modelClass = $this->modelClass();
        $item = $modelClass::create($validated);

        return redirect()
            ->route($this->routePrefix().'.edit', $item->id)
            ->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
    }

    public function edit(Request $request, int|string $id)
    {
        return view($this->viewPrefix().'.edit', [
            'title' => $this->title(),
            'item' => $this->findModel($id),
            'routePrefix' => $this->routePrefix(),
            'viewPrefix' => $this->viewPrefix(),
        ]);
    }

    public function update(Request $request, int|string $id)
    {
        $requestClass = $this->updateRequestClass();

        /** @var FormRequest $validatedRequest */
        $validatedRequest = app($requestClass);
        $validated = $validatedRequest->validated();

        $item = $this->findModel($id);
        $item->update($validated);

        return redirect()
            ->route($this->routePrefix().'.edit', $item->id)
            ->with('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
    }

    public function destroy(int|string $id)
    {
        $item = $this->findModel($id);
        $item->delete();

        return redirect()
            ->route($this->routePrefix().'.index')
            ->with('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }
}