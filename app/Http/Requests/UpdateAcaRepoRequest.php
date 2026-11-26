<?php

namespace App\Http\Requests;

use App\Models\AcaRepo;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateAcaRepoRequest extends StoreAcaRepoRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        if ($user === null) {
            return false;
        }

        if ($user->isSuperAdmin() || $user->isAdmin() || $user->isLibrarian()) {
            return true;
        }

        $acaRepo = $this->route('acaRepo');
        $patron = $user->isPatron() ? $user->patron : null;

        return $acaRepo instanceof AcaRepo
            && $patron !== null
            && (int) $acaRepo->submitted_by === (int) $patron->id
            && $acaRepo->status === 'rejected';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return parent::rules();
    }

    protected function failedAuthorization()
    {
        $user = $this->user();
        $acaRepo = $this->route('acaRepo');

        if ($user !== null && $user->isPatron()) {
            $status = $acaRepo instanceof AcaRepo ? $acaRepo->status : null;

            if ($status === 'rejected') {
                throw new HttpResponseException(
                    response()->json([
                        'status' => 'error',
                        'message' => 'You are not authorized to update this academic repository submission.',
                    ], 403)
                );
            }

            throw new HttpResponseException(
                response()->json([
                    'status' => 'error',
                    'message' => 'You are not authorized to manage academic repositories.',
                ], 401)
            );
        }

        parent::failedAuthorization();
    }
}
