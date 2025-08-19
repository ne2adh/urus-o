<?php

namespace App\Services;

use App\Repositories\ParticipanteRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Participante;

class ParticipanteService implements ParticipanteServiceInterface
{
    private ParticipanteRepositoryInterface $repo;

    public function __construct(ParticipanteRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getAll(): Collection
    {
        return $this->repo->all();
    }

    public function getById(int $id): ?Participante
    {
        return $this->repo->find($id);
    }

    public function create(array $data): Participante
    {
        // Aquí podrías añadir lógica antes de crear
        return $this->repo->create($data);
    }

    public function update(int $id, array $data): bool
    {
        // Lógica adicional si hiciera falta
        return $this->repo->update($id, $data);
    }

    public function delete(int $id): bool
    {
        // Comprobaciones previas si se requieren
        return $this->repo->delete($id);
    }
}
