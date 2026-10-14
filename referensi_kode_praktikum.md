# Referensi Kode Praktikum: CRUD & Auth

Dokumen ini berisi potongan kode inti yang akan kita gunakan selama sesi praktikum. 
**Perhatian:** Lokasi folder dan nama *file* untuk setiap potongan kode akan dipandu langsung oleh asisten praktikum di depan kelas. Perhatikan layar presentasi untuk tahu di mana kode ini harus diletakkan!

---

## BAGIAN 1: IMPLEMENTASI CRUD

### 1.1 Konfigurasi Database (CRUD)
Sesuaikan pengaturan *environment* utama agar aplikasi terhubung ke database `dbexample`.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dbexample
DB_USERNAME=root
DB_PASSWORD=
```

### 1.2 Model Customer
Menjembatani interaksi aplikasi dengan tabel `customers`.

```php
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    public $timestamps = false;
    protected $fillable = [
        'first_name',
        'last_name',
    ];

}
```

### 1.3 Controller Customer
Pusat logika untuk fungsi Create, Read, Update, dan Delete.

```php
<?php

namespace App\Http\Controllers;

use App\Models\Customer as ModelsCustomer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index() {
        $customers = ModelsCustomer::all();
        return view('index',['customers'=>$customers]);
    }

    public function create(){
        return view('create');
    }

    public function store(Request $request){
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
        ]);

        $customer = ModelsCustomer::create($validatedData);

        return redirect()->route('customer.index');
    }

    public function edit($id){
        $customer = ModelsCustomer::findOrFail($id);
        return view('edit', ['customer'=>$customer]);
    }

    public function update(Request $request, $id)
    {
        $customer = ModelsCustomer::findOrFail($id);

        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
        ]);

        $customer->update($validatedData); 

        return redirect()->route('customer.index');
    }

    public function delete($id){
        $customer = ModelsCustomer::findOrFail($id);
        $customer->delete();
        return redirect()->route('customer.index');
    }
}
```

### 1.4 Routing CRUD
Mendaftarkan URL yang dibutuhkan untuk fitur Customer. Pada bentuk akhir, rute-rute ini diletakkan di dalam group `auth` agar hanya bisa diakses setelah login (lihat bentuk akhirnya di Bagian 2.4).

```php
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

// ROUTE CRUD CUSTOMER (diletakkan di dalam group auth)
Route::middleware('auth')->group(function () {
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer.create');
    Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('/customer/edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::post('/customer/update/{id}', [CustomerController::class, 'update'])->name('customer.update');
    Route::get('/customer/delete/{id}', [CustomerController::class, 'delete'])->name('customer.delete');
});
```

### 1.5 Views (Antarmuka) Customer

**A. Halaman Tabel Daftar Customer**
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CRUD Customer</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <p><a href="{{ route('dashboard.index') }}">Dashboard</a></p>
    <a href="{{ route('customer.create') }}" class="btn btn-primary">Add New Customer</a> <table>
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th colspan="2">Action</th> </tr>
        </thead>
        <tbody>
            @foreach ($customers as $row)
            <tr>
                <td>{{ $row->first_name }}</td>
                <td>{{ $row->last_name }}</td>
                <td>
                    <a href="{{ route('customer.edit', $row->id) }}" class="btn btn-warning">Edit</a>
                </td>
                <td>
                    <a href="{{ route('customer.delete', $row->id) }}" class="btn btn-danger">Delete</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
```

**B. Form Tambah Customer Baru**
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Add New Customer</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <p><a href="{{ route('customer.index') }}">Back to Customer List</a></p>
    <form action="{{ route('customer.store') }}" method="post" class="card">
        @csrf
        <p>First Name : <input type="text" name="first_name"></p>
        <p>Last Name : <input type="text" name="last_name"></p>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</body>
</html>
```

**C. Form Edit Customer**
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Customer</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <p><a href="{{ route('customer.index') }}">Back to Customer List</a></p>
    <form action="{{ route('customer.update',$customer->id) }}" method="post" class="card">
        @csrf
        <p>First Name : <input type="text" name="first_name" value="{{ $customer->first_name }}"></p>
        <p>Last Name : <input type="text" name="last_name" value="{{ $customer->last_name }}"></p>
        <button type="submit" class="btn btn-primary">Save</button>
    </form>
</body>
</html>
```

---

## BAGIAN 2: IMPLEMENTASI FORM VALIDATION & AUTHENTICATION

*(Catatan: Pastikan database kalian sudah menggunakan `dbauth` atau tabel `users` sudah tersedia sesuai instruksi sebelumnya).*

### 2.1 Model Akun Pengguna (User)
```php
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public $timestamps = false;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
```

### 2.2 Controller Auth (Login & Register)
Pusat logika untuk memvalidasi pendaftaran dan mengecek akses *login*.

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function createRegister()
    {
        return view('auth.register');
    }

    public function storeRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard.index');
    }

    public function createLogin()
    {
        return view('auth.login');
    }

    public function storeLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended(route('dashboard.index'))->
            with('success', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'Jujur gaada nih!',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
```

### 2.3 Controller Dashboard
```php
<?php
namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index() {
        return view('dashboard');
    }
}
```

### 2.4 Routing (Filter & Auth)
Bentuk akhir file rute utama: rute CRUD diletakkan di dalam group `auth` sehingga hanya bisa diakses setelah login.

```php
<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;

// ROUTE AUTHENTICATION (Hanya untuk tamu)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'createRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'storeRegister'])->name('register.store');
    Route::get('/login', [AuthController::class, 'createLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'storeLogin'])->name('login.store');
});

// ROUTE TERPROTEKSI (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // CRUD CUSTOMER (hanya bisa diakses setelah login)
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer.index');
    Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer.create');
    Route::post('/customer/store', [CustomerController::class, 'store'])->name('customer.store');
    Route::get('/customer/edit/{id}', [CustomerController::class, 'edit'])->name('customer.edit');
    Route::post('/customer/update/{id}', [CustomerController::class, 'update'])->name('customer.update');
    Route::get('/customer/delete/{id}', [CustomerController::class, 'delete'])->name('customer.delete');
});
```

### 2.5 Views (Antarmuka) Auth & Dashboard

**A. Form Halaman Login**
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>Login</h1>
    <hr>
    @if (session('success'))
        <p class="alert-success">{{ session('success') }}</p>
    @endif
    <form action="{{ route('login.store') }}" method="post" class="card">
        @csrf 
        <p>Email <input type="text" name="email" value="{{ old('email') }}"></p>
        <p>Password <input type="password" name="password"></p>

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <button type="submit" class="btn btn-primary">Login</button>
        <p>
            Don't have an account yet? <a href="{{ route('register') }}">Click here</a> to register
        </p>
    </form>
</body>
</html>
```

**B. Form Halaman Register**
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>Register</h1>
    <hr>

    <form action="{{ route('register.store') }}" method="post" class="card">
        @csrf 
        <p>Name <input type="text" name="name" value="{{ old('name') }}"></p>
        <p>Email <input type="text" name="email" value="{{ old('email') }}"></p>
        <p>Password <input type="password" name="password"></p>
        <p>Confirm Password <input type="password" name="password_confirmation"></p> @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <button type="submit" class="btn btn-primary">Register</button>
        <p>
            Already have an account? <a href="{{ route('login') }}">Click here</a> to login
        </p>
    </form>
</body>
</html>
```

**C. Halaman Utama (Dashboard)**
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <h1>Hello there!</h1>
    
    <p>You Logged in using {{ Auth::user()->email }}</p>
    <br>

    <p><a href="{{ route('customer.index') }}" class="btn btn-primary">Manage Customers</a></p>
    <br>

    <form action="{{ route('logout') }}" method="post">
        @csrf
        <button type="submit" class="btn btn-danger">Logout here</button>
    </form>
</body>
</html>
```

---

## BAGIAN 3: STYLING SEDERHANA (CSS GLOBAL)

Satu file CSS global di `public/css/style.css`, dimuat di setiap view dengan:

```html
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
```

Class yang tersedia: `btn` + `btn-primary` (biru) / `btn-warning` (kuning) / `btn-danger` (merah) untuk tombol & link aksi, `card` untuk bingkai form, `alert-error` / `alert-success` untuk pesan validasi. Tabel dan input text/password sudah distyle otomatis tanpa class tambahan.