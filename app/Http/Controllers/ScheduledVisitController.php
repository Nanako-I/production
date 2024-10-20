<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduledVisit;
use App\Models\Transport;

class ScheduledVisitController extends Controller
{
    // 送迎ステータスを更新する処理
    public function updateTransport(Request $request, $scheduledVisitId)
    {
        // ScheduledVisit の取得
        $scheduledVisit = ScheduledVisit::find($scheduledVisitId);

        if (!$scheduledVisit) {
            return response()->json(['error' => 'Scheduled visit not found'], 404);
        }

        // Transport レコードが存在するか確認し、なければ作成する
        $transport = Transport::firstOrCreate(
            ['scheduled_visit_id' => $scheduledVisitId],
            [
                'pickup_time' => $scheduledVisit->pick_up_time,
                'dropoff_time' => $scheduledVisit->drop_off_time,
                'people_id' => $scheduledVisit->people_id
            ]
        );

        // リクエストデータに基づいて送迎ステータスを更新
        if ($request->has('pickup_completed')) {
            $transport->pickup_completed = $request->pickup_completed;
        }

        if ($request->has('dropoff_completed')) {
            $transport->dropoff_completed = $request->dropoff_completed;
        }

        $transport->save();

        return response()->json(['success' => 'Transport status updated']);
    }
}
