<?php

namespace App\Http\Livewire;

use Exception;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\CategoryExport;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Admin extends Component
{
    use WithPagination, LivewireAlert;

    public $user_id, $name, $email, $password, $password_confirmation, $block, $search;
    public $sortDirection = 'desc', $sortColumnName = 'created_at', $per_page = 5, $selectedProducts = [];
    public $form = false, $selectAll = false, $bulkDisabled = false; 

    protected $categories, $queryString=['search'];

    protected $rules = [
        'name'=>'required',
        'email'=>'required|email|unique:users',
        'password'=>'required|min:6|confirmed'
    ];

    protected $messages = [
        'name.required'=>'İstifadəçi xanasını boş buraxmayın',
        'email.required'=>'Email xanasını boş buraxmayın',
        'email.email'=>'Daxil etdiyiniz məlumat email formatında deyil',
        'email.unique'=>'Bu email mövcuddur',
        'password.required'=>'Parol xanasını boş buraxmayın',
        'password.min'=>'Parol 6 simvoldan az ola bilməz',
        'password.confirmed'=>'Parol ilə təkrar parol uyğun deyil',
    ];

    public function render()
    {
        $query = User::query();
 
        $this->bulkDisabled = count($this->selectedProducts)<1;

        if($this->search){
            $query->where('name', 'like', "%{$this->search}%")
            ->orWhere('email', 'like', "%{$this->search}%");
        }

        return view('livewire.admin',[
            'users'=>$query->orderBy($this->sortColumnName, $this->sortDirection)->paginate($this->per_page),
        ]);
    } 

    public function block($id){
        try{
            User::findOrFail($id)->fill([
                'status'=>0,
            ])->save();
            $this->alert('success', 'İstifadəçi blok edildi');    
        }
        catch(Exception $e){
            session()->flash('error', 'Nə isə düz getmədi!!');
        }
    }   
    public function unblock($id){
        try{
            User::findOrFail($id)->fill([
                'status'=>1,
            ])->save();
            $this->alert('success', 'İstifadəçi blokdan çıxarıldı');    
        }
        catch(Exception $e){
            session()->flash('error', 'Nə isə düz getmədi!!');
        }
    } 

    public function deleteSelected(){
        User::query()->whereIn('id', $this->selectedProducts)->delete();
        $this->selectedProducts = [];
        $this->selectAll = false;
    }

    public function updatedSelectAll($value){
        if ($value) {
            $this->selectedProducts = User::pluck('id')->toArray() ;
        } else {
            $this->selectedProducts = [];
        }
    }

    public function resetFields(){
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function store(){
        $this->validate();
        date_default_timezone_set('Asia/Baku');
        try{
            User::create([
                'name'=>$this->name,
                'email'=>$this->email,
                'password'=>bcrypt($this->password),
            ]);
            $this->alert('success', 'İstifadəçi uğurla daxil edildi');
            $this->dispatchBrowserEvent('closeModal');
            $this->resetFields();
        }
        catch(Exception $e){
            session()->flash('error', 'Nə isə düz getmədi!!');
            $this->resetFields();
        }
    }

    public function edit($id){
        $user = User::findOrFail($id);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = $user->password;
        $this->password_confirmation = $user->password_confirmation;
        $this->user_id = $user->id;
        $this->form=true;
    }

    public function update(){
        $this->validate([
            'name'=>'required',
            'email'=>'required|email',
        ]);
        try{
            User::findOrFail($this->user_id)->fill([
                'name'=>$this->name,
                'email'=>$this->email,
            ])->save();
            $this->alert('success', 'İstifadəçi uğurla yeniləndi');    
            $this->dispatchBrowserEvent('closeModal');
            $this->cancel();
        }
        catch(Exception $e){
            session()->flash('error', 'Nə isə düz getmədi!!');
            $this->cancel();
        }
    }

    public function destroy($id){
        User::findOrFail($id)->delete();
    }

    public function cancel(){
        $this->form = false;
        $this->resetFields();
    }
    
    public function updated($property){
        $this->validateOnly($property);
        if($property == 'search'){
            $this->resetPage();
        }
    }

    public function sortBy($columnName){
        if ($this->sortColumnName === $columnName) {
            $this->sortDirection = $this->swapSortDirection();
        }
        else {
            $this->sortDirection = 'asc';
        }
        $this->sortColumnName = $columnName;
        $this->resetPage();
    }

    public function swapSortDirection(){
        return $this->sortDirection === 'asc' ? 'desc' : 'asc';
    }
    
    public function updatedCheckboxAll()
    {
        $this->checkbox_values = [];

        if ($this->checkbox_all) {
            $this->models()->paginate($this->per_page)->each(function ($model) {
                $this->checkbox_values[] = (string) $model->{$this->checkbox_attribute};
            });
        }
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function export() 
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}