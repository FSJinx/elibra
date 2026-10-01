<?php

namespace App\Services;

use App\Models\User;
use App\Models\Branch;
use App\Models\Campus;
use App\Models\Media;
use App\Models\Patron;
use App\Models\PatronType;
use App\Services\MediaService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    /**
     * Returns User Information
     */
    public function index()
    {
        $user = auth('api')->user();

        if (! $user instanceof User) {
            return [];
        }

        $campus = null;
        $branch = null;

        $data = [
            ...$user->toArray(),
        ];

        switch ($user?->role) {
            case 'super_admin':
                break;

            case 'admin':
            case 'librarian':
                $librarian = $user->librarian;

                if ($librarian) {
                    $branch = Branch::where('id', '=', $librarian->branch->id)->first();
                    $campus = Campus::where('id', '=', $librarian->branch->campus->id)->first();

                    // Remove nested relationships before converting user to array
                    $user->librarian->unsetRelation('branch');
                } else {
                    $campus = Campus::where('id', '=', $user->campus_id)->first();
                }
                break;

            case 'patron':
                break;

            default:
                break;
        }

        return [
            ...$data,
            'campus' => $campus,
            'branch' => $branch,
        ];
    }

    public function register(array $data, UploadedFile $profilePicture): array
    {
        return DB::transaction(function () use ($data, $profilePicture) {
            $credentials = [
                'username' => $data['username'],
                'password' => $data['password'],
            ];

            $campus = Campus::findOrFail($data['campus_id']);
            $patronType = PatronType::findOrFail($data['patron_type_id']);

            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'middle_initial' => $data['middle_initial'] ?? null,
                'sex' => $data['sex'],
                'birthdate' => $data['birthdate'],
                'contact_number' => $data['contact_number'],
                'email' => $data['email'],
                'username' => $data['username'],
                'password' => Hash::make($data['password']),
                'role' => 'patron',
                'status' => 'active',
                'campus_id' => $campus->id,
            ]);

            $profileMedia = app(MediaService::class)->store($profilePicture, Media::PROFILE);
            $user->update(['profile_picture_id' => $profileMedia->id]);

            $patron = Patron::create([
                'user_id' => $user->id,
                'patron_type_id' => $data['patron_type_id'],
                'program_id' => $data['program_id'],
                'ebc_number' => $this->generateEbcNumber($campus->code, $patronType->key),
            ]);

            UserService::assignRoleAndPermissions($user, 'patron');

            $token = JWTAuth::attempt($credentials);

            return [
                'token' => $token,
                'user' => $user->fresh(),
                'patron' => $patron->fresh(),
            ];
        });
    }

    private function generateEbcNumber(string $campusCode, ?string $patronTypeKey): string
    {
        $prefix = 'EBC-'.strtoupper($campusCode).'-'.strtoupper($patronTypeKey ?: 'PATRON').'-';
        $lastNumber = Patron::query()
            ->where('ebc_number', 'like', $prefix.'%')
            ->lockForUpdate()
            ->pluck('ebc_number')
            ->map(fn (string $ebcNumber) => (int) str_replace($prefix, '', $ebcNumber))
            ->max() ?? 0;

        return $prefix.str_pad((string) ($lastNumber + 1), 6, '0', STR_PAD_LEFT);
    }
}
