<?php

namespace App\Repositories;

use App\Models\Participante;
use Illuminate\Database\Eloquent\Collection;

class EloquentParticipanteRepository implements ParticipanteRepositoryInterface
{
    protected Participante $model;

    public function __construct(Participante $model)
    {
        $this->model = $model;
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function find(int $id): ?Participante
    {
        return $this->model->find($id);
    }

    public function create(array $data): Participante
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $participante = $this->find($id);
        if (! $participante) {
            return false;
        }
        return $participante->update($data);
    }

    public function delete(int $id): bool
    {
        $participante = $this->find($id);
        if (! $participante) {
            return false;
        }
        return $participante->delete();
    }
}
