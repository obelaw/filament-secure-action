<?php

namespace Obelaw\Filament\SecureAction\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Obelaw\Filament\SecureAction\Rules\ValidMfaCode;

class ObelawSecureActionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/secure-action.php', 'obelaw.secure-action');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/obelaw-secure-action.php' => config_path('obelaw/secure-action.php'),
        ], 'obelaw-secure-action');

        $this->booted(function () {
            if (!Action::hasMacro('requiresPasswordConfirmation')) {
                Action::macro('requiresPasswordConfirmation', function (bool $condition = true) {
                    if (!$condition) {
                        return $this;
                    }

                    return $this->requiresConfirmation()
                        ->form([
                            TextInput::make(config('obelaw.secure-action.password.form.field'))
                                ->belowContent(config('obelaw.secure-action.password.form.content'))
                                ->password()
                                ->currentPassword()
                                ->required()
                                ->dehydrateStateUsing(fn ($state) => null),
                        ]);
                });
            }

            if (!Action::hasMacro('requiresMFAConfirmation')) {
                Action::macro('requiresMFAConfirmation', function (bool $condition = true) {
                    if (!$condition) {
                        return $this;
                    }

                    return $this->requiresConfirmation()
                        ->form([
                            TextInput::make(config('obelaw.secure-action.mfa.form.field'))
                                ->label(config('obelaw.secure-action.mfa.form.label'))
                                ->placeholder(config('obelaw.secure-action.mfa.form.placeholder'))
                                ->required()
                                ->numeric()
                                ->maxLength(config('obelaw.secure-action.mfa.form.max_length'))
                                ->minLength(config('obelaw.secure-action.mfa.form.min_length'))
                                ->rules([new ValidMfaCode()]),
                        ]);
                });
            }
        });
    }
}
