<?php

namespace App\Notifications;

use App\Models\Cliente;
use App\Models\OrdemServico;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OsStatusNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Cliente $cliente,
        public OrdemServico $ordemServico,
    )
    {
        
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $clienteNome = $notifiable->nome ?? 'Cliente';
        $osId = $this->ordemServico->id;
        $novoStatus = $this->ordemServico->status;
        $modelo = $this->ordemServico->equipamento?->modelo ?? 'Equipamento';

        return (new MailMessage)
            // Assunto claro
            ->subject("Status da OS #{$osId} foi atualizado para: {$novoStatus}")
            
            // Saudação
            ->greeting("Olá, {$clienteNome}!")
            
            // Linha principal com o novo status em destaque
            ->line("Seu equipamento ({$modelo}) da Ordem de Serviço \#{$osId} teve uma atualização.")
            
            // Linha que o cliente realmente quer ver
            ->line("O **novo status** do seu serviço é: **{$novoStatus}**.")

            // Botão para ver os detalhes
            ->action('Ver Detalhes da OS', route('os.show', $osId))
            
            // Encerramento
            ->line('Qualquer dúvida sobre o andamento, entre em contato.');

            return redirect()->route('os.show', $os->id)->with('success', 'Status atualizado com sucesso!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
