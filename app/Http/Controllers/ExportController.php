<?php

namespace App\Http\Controllers;

use App\Exports\DashboardExport;
use App\Exports\EventDetailExport;
use App\Exports\RespondentsExport;
use App\Models\Event;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    /**
     * Export data tabel dashboard (event terakhir) ke Excel.
     */
    public function dashboard()
    {
        $latestEvent = Event::latest('tanggal')->first();
        $eventName = $latestEvent ? $latestEvent->nama_event : 'Kosong';
        $filename = 'Dashboard-' . str_replace(' ', '_', $eventName) . '-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new DashboardExport(), $filename);
    }

    /**
     * Export data responden per event ke Excel.
     */
    public function eventDetail(Event $event)
    {
        $filename = 'Event-' . str_replace(' ', '_', $event->nama_event) . '-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new EventDetailExport($event), $filename);
    }

    /**
     * Export seluruh data responden ke Excel.
     */
    public function respondents()
    {
        $filename = 'Seluruh-Responden-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new RespondentsExport(), $filename);
    }
}
