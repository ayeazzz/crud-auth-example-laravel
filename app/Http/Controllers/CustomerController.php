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
