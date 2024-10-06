<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Notification;
use App\Models\Rental;
use App\Models\Rturn;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $availableCarsCount = Car::where('status', 'available')->count();
        $pendingApprovalCount = Rental::where('user_id', Auth::id())->where('status', 'pending')->count();
        $rentedCarsCount = Rental::where('user_id', Auth::id())->where('status', 'active')->count();
        $listedCarsCount = Car::where('owner_id', Auth::id())->where('status', 'available')->count();
        $notification = Notification::where('email', Auth::user()->email)->get();
        $notificationCount = $notification->count();

        return view('dashboard.main', [
            'availableCarsCount' => $availableCarsCount,
            'pendingApprovalCount' => $pendingApprovalCount,
            'rentedCarsCount' => $rentedCarsCount,
            'listedCarsCount' => $listedCarsCount,
            'notification' => $notification,
            'notificationCount' => $notificationCount,
        ]);
    }

    public function isAvailable(Request $request)
    {
        $validatedData = $request->validate([
            'mobil' => 'required|exists:cars,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        if ($validatedData['tanggal_mulai'] === $validatedData['tanggal_selesai']) {
            return response()->json([
                'is_available' => false,
                'message' => 'Minimal 1 hari persewaan diperlukan.'
            ]);
        }

        $car = Car::find($validatedData['mobil']);

        if ($car->status === 'not_available') {
            return response()->json([
                'is_available' => false,
                'message' => 'Mobil sedang dalam proses persetujuan.'
            ]);
        }

        if ($car->status === 'maintenance') {
            return response()->json([
                'is_available' => false,
                'message' => 'Mobil sedang dalam perawatan dan tidak tersedia untuk disewa.'
            ]);
        }

        $existingRentals = Rental::where('car_id', $validatedData['mobil'])
            ->where(function ($query) use ($validatedData) {
                $query->where(function ($query) use ($validatedData) {
                    $query->where('status', 'active')
                        ->where(function ($query) use ($validatedData) {
                            $query->where('start_date', '<', $validatedData['tanggal_selesai'])
                                ->where('end_date', '>', $validatedData['tanggal_mulai']);
                        });
                })->orWhere('status', 'pending');
            })
            ->get();

        if ($existingRentals->where('status', 'active')->count() > 0) {
            $nextAvailableDate = $existingRentals->where('status', 'active')->max('end_date');

            if ($nextAvailableDate) {
                return response()->json([
                    'is_available' => false,
                    'message' => 'Mobil sedang disewa hingga tanggal ' . \Carbon\Carbon::parse($nextAvailableDate)->format('d-m-Y') . '.'
                ]);
            }
        }

        $completedRentals = Rental::where('car_id', $validatedData['mobil'])
            ->where('status', 'completed')
            ->where('end_date', '<', now())
            ->exists();

        if ($completedRentals) {
            return response()->json([
                'is_available' => true,
                'message' => 'Mobil tersedia untuk disewa, telah selesai disewa sebelumnya.'
            ]);
        }

        if ($existingRentals->where('status', 'pending')->count() > 0) {
            return response()->json([
                'is_available' => false,
                'message' => 'Mobil sedang dalam proses persetujuan.'
            ]);
        }

        return response()->json([
            'is_available' => true,
            'message' => 'Mobil tersedia untuk disewa.'
        ]);
    }



    public function rent(Request $request)
    {
        $valid = $request->validate([
            'tanggal_selesai' => 'required|date',
            'tanggal_mulai' => 'required|date|after:start_date',
            'mobil' => 'required|exists:cars,id',
        ]);

        Rental::create([
            'user_id' => Auth::id(),
            'car_id' => $valid['mobil'],
            'start_date' => $valid['tanggal_mulai'],
            'end_date' => $valid['tanggal_selesai'],
            'status' => 'active',
        ]);

        Car::where('id', $valid['mobil'])->update(['status' => 'rented']);

        Notification::create([
            'email' => Auth::user()->email,
            'time' => Carbon::now()->format('F j, Y'),
            'message' => "Permintaan untuk menyewa sudah dikirim, Mohon tunggu sebentar!",
            'type' => 'info'
        ]);

        return redirect()->back()->with('message', 'Berhasil dikirim! Selanjutnya tunggu konfirmasi dari staff kami');
    }

    public function sewa()
    {
        $cars = Car::all();
        $notification = Notification::where('email', Auth::user()->email)->get();
        $notificationCount = $notification->count();

        return view('pages.sewa', [
            'cars' => $cars,
            'notification' => $notification,
            'notificationCount' => $notificationCount,
        ]);
    }

    public function rental()
    {
        $rents = Rental::with('car')
            ->where('user_id', Auth::id())
            ->get();
        $notification = Notification::where('email', Auth::user()->email)->get();
        $notificationCount = $notification->count();

        return view('pages.rental', [
            'rents' => $rents,
            'notification' => $notification,
            'notificationCount' => $notificationCount,
        ]);
    }

    public function rturn()
    {
        $rentals = Rental::with('car')
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->get();

        foreach ($rentals as $rental) {
            $start = Carbon::parse($rental->updated_at);
            $end = Carbon::parse($rental->end_date);

            if ($end->isFuture()) {
                $end = Carbon::now();
            }

            $durationInDays = $start->diffInDays($end);

            $rental->duration = max((int)$durationInDays, 1);

            $dailyRate = $rental->car->daily_rental_rate;
            $totalCost = $dailyRate * $rental->duration;

            $rental->total_cost = round($totalCost);
        }
        $notification = Notification::where('email', Auth::user()->email)->get();
        $notificationCount = $notification->count();

        return view('pages.return', [
            'rentals' => $rentals,
            'notification' => $notification,
            'notificationCount' => $notificationCount,
        ]);
    }

    public function rentPost(Request $request)
    {
        try {
            $return = new Rturn();
            $return->rental_id = $request->input('rental_id');
            $return->car_id = $request->input('car_id');
            $return->total_fee = $request->input('total_fee');
            $return->returned_at = Carbon::parse($request->input('returned_at'))->format('Y-m-d H:i:s');
            $return->condition = $request->input('condition', 'good');
            $return->comments = $request->input('comments');
            $return->save();

            $rental = Rental::find($request->input('rental_id'));
            if ($rental) {
                $rental->status = 'completed';
                $rental->save();
            }

            Car::where('id', $request->input('car_id'))->update(['status' => 'available']);

            Notification::create([
                'email' => Auth::user()->email,
                'time' => Carbon::now()->format('F j, Y'),
                'message' => "Mobil berhasil dikembalikan!",
                'type' => 'info'
            ]);

            return redirect()->route('return')
                ->with('success', 'Mobil berhasil dikembalikan!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengembalikan mobil: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function ownerRental(Request $request)
    {
        try {
            Car::create([
                'plate_number' => $request->input('plate_number'),
                'brand' => $request->input('merek'),
                'model' => $request->input('model'),
                'year' => $request->input('year'),
                'color' => $request->input('color'),
                'mileage' => $request->input('mileage'),
                'daily_rental_rate' => $request->input('tarif_sewa'),
                'status' => 'not_available',
                'owner_id' => Auth::id(),
            ]);

            Notification::create([
                'email' => Auth::user()->email,
                'time' => Carbon::now()->format('F j, Y'),
                'message' => "Data mobil anda Berhasil dikirim! Selanjutnya tunggu konfirmasi dari staff kami",
                'type' => 'info'
            ]);

            return redirect()->back()->with('success', 'data mobil berhasil dikirim! tunggu persetujuan dari staff!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyewakan mobil: ' . $e->getMessage());
        }
    }

    public function owner()
    {
        $notification = Notification::where('email', Auth::user()->email)->get();
        $notificationCount = $notification->count();

        return view('pages.owner-rental', [
            'notification' => $notification,
            'notificationCount' => $notificationCount,
        ]);
    }

    public function daftarMobil()
    {
        $cars = Car::all();
        return view('pages.daftar-mobil', ['cars' => $cars]);
    }
}
