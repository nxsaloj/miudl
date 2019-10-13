<?php

namespace App\Listeners;

use App\Events\EventInserted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventInsertedListener
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
     * @param  EventInserted  $event
     * @return void
     */
    public function handle(EventInserted $event)
    {
        try{
            $this->repository->create($event->params);
            \Log::channel('event')->info('Evento insertado: '.$event->params['Actividad'].'; Nombre: '.$event->params['ItemNombre'].', id: '.$event->params['ItemId']);
        }
        catch(\Illuminate\Database\QueryException $e){
            \Log::channel('error')->warning('Event QueryException ' . $e->getMessage());
        }
        catch (\Exception $e) {
            \Log::channel('error')->warning('Event Exception ' . $e->getMessage());
        }
    }
}
