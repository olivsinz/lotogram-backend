<?php

namespace App\Http\Resources\Dashboard\Auth;

use App\Models\Method;
use Illuminate\Http\Request;
use App\Service\TransactionService;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Dashboard\RoleResource;
use App\Http\Resources\Dashboard\SiteResource;
use App\Http\Resources\Dashboard\TitleResource;
use App\Http\Resources\Dashboard\MethodResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Dashboard\PermissionResource;

class LoginResource extends JsonResource
{
    public function __construct($resource, private $useTfa = false)
    {
        parent::__construct($resource);
        $this->useTfa = $useTfa;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data =  [
            'id' => $this->uuid,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'email' => $this->email,
            'language' => $this->language,
            'phone' => $this->phone,
            'birth_date' => $this->birth_date,
            'national_id' => $this->national_id,
            'type' => $this->type,
            'balances' => $this->when(Auth::user(), fn () => [
                'balance' => TransactionService::balance(Auth::user()),
                'withdrawable_balance' => TransactionService::withdrawableBalance(Auth::user()),
                'bonus_balance' => TransactionService::bonusBalance(Auth::user())
            ]),
            'api_token' => $this->api_token,
            'api_token_create_time' => $this->api_token_create_time,
            'api_token_life_time' => $this->api_token_life_time,
            'has_tfa' => $this->has_tfa,
            'tfa_method' => $this->tfa_method,
            'security' => $this->security,
            'password_change_required' => $this->password_change_required,
            'password_changed_at' => $this->password_changed_at,
            'email_verified_at' => $this->email_verified_at,
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),
            'settings' => collect($this->setting->setting)->isEmpty() ? null : $this->setting->setting,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'pusher_client_url' => $this->when(config('app.env') == 'local' || config('app.env') == 'test', fn () => config('app.url') . '/pusher/test/client' . '?api_token=' . $this->api_token . '&user_id=' . $this->uuid),
        ];

        return $this->useTfa
            ? collect($data)->only(['email', 'api_token', 'api_token_create_time', 'api_token_life_time', 'has_tfa', 'tfa_method'])->toArray()
            : $data;
    }
}
