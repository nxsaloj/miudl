<?php

namespace App\Listeners;

use App\Events\EventUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventUpdatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    private $repository;
    public function __construct(\miudl\Logging\LogRepositoryInterface $_repository)
    {
        $this->repository = $_repository;
    }

    /**
     * Handle the event.
     *
     * @param  EventUpdated  $event
     * @return void
     */
    public function handle(EventUpdated $event)
    {
        try{
            $this->repository->create($event->params);
            \Log::channel('event')->info('Evento destruido: '.$event->params['Actividad'].'; Nombre: '.$event->params['ItemNombre'].', id: '.$event->params['ItemId']);
        }
        catch(\Illuminate\Database\QueryException $e){
            \Log::channel('error')->warning('Event QueryException ' . $e->getMessage());
        }
        catch (\Exception $e) {
            \Log::channel('error')->warning('Event Exception ' . $e->getMessage());
        }
    }
}
