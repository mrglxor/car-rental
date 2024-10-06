<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Notification;
use App\Models\Rental;
use App\Models\Rturn;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function renting()
    {
        $rents = Rental::with('car')->with('user')->get();

        return view('admin.renting', ['rents' => $rents]);
    }
    public function rentingOut()
    {
        $cars = Car::with('owner')->where('status', 'not_available')->get();
        return view('admin.renting-out', ['cars' => $cars]);
    }
    public function active(Request $request, $id)
    {
        $rent = Rental::find($id);

        if (!$rent) {
            return redirect()->back()->with('error', 'Rental tidak ditemukan');
        }

        $rent->update(['status' => 'active']);

        $car = Car::find($rent->car_id);
        if ($car) {
            $car->update(['status' => 'rented']);
        }

        $user = User::find($rent->user_id);
        if ($user) {
            $email = $user->email;

            Notification::create([
                'email' => $email,
                'time' => Carbon::now()->format('F j, Y'),
                'message' => "Yeay! Menyewa mobil berhasil. Ambil mobil segera!",
                'type' => 'info',
            ]);

            return redirect()->back()->with('success', 'Rental berhasil disetujui dan notifikasi dikirim.');
        }

        return redirect()->back()->with('error', 'Gagal mengirim notifikasi');
    }
    public function canceled(Request $request, $id)
    {
        $rent = Rental::find($id);

        if (!$rent) {
            return redirect()->back()->with('error', 'Rental tidak ditemukan');
        }

        $rent->update(['status' => 'canceled']);

        $car = Car::find($rent->car_id);
        if ($car) {
            $car->update(['status' => 'available']);
        }

        $user = User::find($rent->user_id);
        if ($user) {
            $email = $user->email;

            Notification::create([
                'email' => $email,
                'time' => Carbon::now()->format('F j, Y'),
                'message' => "Now! Menyewa mobil dibatalkan. cek ulang tanggal, dan coba lagi!",
                'type' => 'danger',
            ]);

            return redirect()->back()->with('success', 'Rental berhasil dibatalkan dan notifikasi dikirim.');
        }

        return redirect()->back()->with('error', 'Gagal mengirim notifikasi');
    }
    public function activeOut(Request $request, $id)
    {
        $car = Car::find($id);

        if (!$car) {
            return redirect()->back()->with('error', 'Mobil tidak ditemukan');
        }

        $car->update(['status' => 'available']);

        Notification::create([
            'email' => $car->owner->email,
            'time' => Carbon::now()->format('F j, Y'),
            'message' => "Selamat mobil anda disetujui untuk disewakan! segera antarkan mobil anda ke tempat kami",
            'type' => 'info',
        ]);

        return redirect()->back()->with('success', 'Mobil berhasil disetujui dan notifikasi dikirim.');
    }

    public function dataMobil()
    {
        $cars = Car::all();
        return view('admin.data-mobil', ['cars' => $cars]);
    }
    public function dataRental()
    {
        $rents = Rental::all();
        return view('admin.data-rental', ['rents' => $rents]);
    }
    public function dataReturn()
    {
        $returns = Rturn::all();
        return view('admin.data-return', ['returns' => $returns]);
    }
    public function dataUser()
    {
        $users = User::all();
        return view('admin.data-user', ['users' => $users]);
    }
    public function delete(Request $request, $type, $id)
    {
        switch ($type) {
            case 'car':
                $model = Car::findOrFail($id);
                break;
            case 'rental':
                $model = Rental::findOrFail($id);
                break;
            case 'return':
                $model = Rturn::findOrFail($id);
                break;
            case 'user':
                $model = User::findOrFail($id);
                break;
            default:
                return redirect()->back()->with('error', 'Tipe data tidak valid.');
        }

        $model->delete();
        return redirect()->back()->with('success', ucfirst($type) . ' berhasil dihapus.');
    }
}
