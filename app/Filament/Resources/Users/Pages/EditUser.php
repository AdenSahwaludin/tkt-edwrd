<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\LogAktivitas;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('resetPassword')
                ->label('Reset Password')
                ->icon('heroicon-o-key')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('Reset Password Pengguna')
                ->modalDescription('Password akan direset ke password sementara dan ditampilkan di sini. Pastikan untuk memberitahu pengguna password baru ini.')
                ->modalSubmitActionLabel('Reset Password')
                ->action(function () {
                    if (! Auth::user()->can('resetPassword', $this->record)) {
                        Notification::make()
                            ->title('Akses Ditolak')
                            ->body('Anda tidak memiliki izin untuk reset password.')
                            ->danger()
                            ->send();

                        return;
                    }

                    $temporaryPassword = Str::random(12);
                    $this->record->update([
                        'password' => Hash::make($temporaryPassword),
                    ]);

                    LogAktivitas::create([
                        'user_id' => Auth::id(),
                        'aksi' => 'reset_password',
                        'deskripsi' => "Reset password untuk pengguna: {$this->record->name}",
                        'model' => 'User',
                        'model_id' => $this->record->id,
                    ]);

                    Notification::make()
                        ->title('Password Direset')
                        ->body("Password baru untuk {$this->record->name}: $temporaryPassword")
                        ->success()
                        ->send();
                }),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
