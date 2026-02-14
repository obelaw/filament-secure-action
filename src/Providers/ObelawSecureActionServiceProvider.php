<?php

namespace Obelaw\Filament\SecureAction\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Auth\MultiFactor\App\AppAuthentication;

class ObelawSecureActionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register bindings or facades here if needed in the future.
    }

    public function boot(): void
    {
        Action::macro('requiresPasswordConfirmation', function (bool $condition = true) {
            if (!$condition) {
                return $this;
            }

            return $this->requiresConfirmation()
                ->form([
                    TextInput::make('password')
                        ->belowContent('Please enter your current password to confirm this action.')
                        ->password()
                        ->currentPassword()
                        ->required()
                ]);
        });

        Action::macro('requiresMFAConfirmation', function (bool $condition = true) {
            if (! $condition) {
                return $this;
            }

            return $this->requiresConfirmation()
                ->form([
                    TextInput::make('code')
                        ->label('Two Factor Code')
                        ->placeholder('######')
                        ->required()
                        ->numeric()
                        ->maxLength(6)
                        ->minLength(6)
                        ->rules([
                            fn() => function ($attribute, $value, $fail) {
                                $isValid = AppAuthentication::make()->verifyCode($value);

                                if (! $isValid) {
                                    $fail('The provided two factor authentication code was invalid.');
                                }
                            },
                        ]),
                ]);
        });
    }
}
