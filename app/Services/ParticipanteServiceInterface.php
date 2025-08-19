<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Participante;

interface ParticipanteServiceInterface
{
    public function getAll(): Collection;
    public function getById(int $id): ?Participante;
    public function create(array $data): Participante;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
}
