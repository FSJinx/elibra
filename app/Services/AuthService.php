<?php

namespace App\Services;

use App\Models\Campus;
use App\Models\Library;
use App\Models\Media;
use App\Models\Patron;
use App\Models\PatronType;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function __construct(
        private UserService $userService
    ) {}

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
        $library = null;

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
                    $library = Library::where('id', '=', $librarian->library->id)->first();
                    $campus = Campus::where('id', '=', $librarian->library->campus->id)->first();

                    // Remove nested relationships before converting user to array
                    $user->librarian->unsetRelation('library');
                } else {
                    $campus = Campus::where('id', '=', $user->campus_id)->first();
                }
                break;

            case 'patron':
                $user->loadMissing(['patron.program', 'patron.patronType', 'campus']);
                $data = [
                    ...$user->toArray(),
                    'patron' => $user->patron,
                ];
                $campus = $user->campus;
                break;

            default:
                break;
        }

        return [
            ...$data,
            'campus' => $campus,
            'library' => $library,
        ];
    }

    public function create(array $data)
    {
        $data = array_merge($data, [
            'role' => 'patron',
            'registration' => 'pending',
        ]);

        $user = $this->userService->create($data);
    }

    public function register(array $data, ?UploadedFile $profilePicture): array
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
                'registration' => 'pending',
                'campus_id' => $campus->id,
            ]);

            if (! empty($profilePicture)) {
                $profileMedia = app(MediaService::class)->store($profilePicture, Media::PROFILE);
                $user->update(['profile_picture_id' => $profileMedia->id]);
            }

            $patron = Patron::create([
                'user_id' => $user->id,
                'patron_type_id' => $data['patron_type_id'],
                'program_id' => $data['program_id'],
                'ebc_number' => $this->generateEbcNumber($campus->code, $patronType->key),
            ]);

            CacheService::invalidate(CacheService::USERS);

            // UserService::assignRoleAndPermissions($user, 'patron');

            // $token = JWTAuth::attempt($credentials);

            return [
                // 'token' => $token,
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
