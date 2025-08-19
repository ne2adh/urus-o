<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Participante;

interface ParticipanteRepositoryInterface
{
    public function all(): Collection;
    public function find(int $id): ?Participante;
    public function create(array $data): Participante;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
