<div>
    @if (session()->has('success'))
    <div class="alert alert-success" role="alert">
        {{session()->get('success')}}
    </div>
    @endif
    @if (session()->has('error'))
    <div class="alert alert-danger" role="alert">
        {{session()->get('error')}}
    </div>
    @endif

    <div class="row">
        <div class="col-lg-4 col-md-4 order-1">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-6 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <span class="fw-semibold d-block mb-1">İstifadəçi sayı</span>
                            <small style="font-size: 1rem" class="text-success fw-semibold">
                                {{ $users->total() }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="my-2">
        @if ($form)
        @include('livewire.admin_edit_form')
        @else
        @include('livewire.admin_store_form')
        @endif
    </div>
    <div class="col-md-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    {{--Search--}}
                    <button style="margin: -0.4% 0" class="float-end" wire:click="export">
                        <i style="font-size:2rem;" class="bi bi-file-earmark-excel-fill"></i>
                    </button>

                    <div class="row g-3 align-items-center float-end">
                        <div class="col-auto">
                            <input wire:model='search' type="search" id="inputPassword6" class="form-control"
                                aria-describedby="passwordHelpInline">
                        </div>
                    </div>

                    <select style="padding: 0 2.4%" wire:model="per_page">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="500">500</option>
                        <option value="1000">1000</option>
                    </select><br><br>

                    <button wire:click.prevent="deleteSelected"
                        onclick="confirm('Are you sure?') || .stopImmediatePropagation()"
                        class="@if ($bulkDisabled) opacity-50 @endif focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                        type="button">Seçilmişləri sil
                    </button>
                    
                    <table id="tableID" class="table">
                        <thead>
                            <tr>
                                <th><input wire:model='selectAll' type="checkbox"></th>
                                <th>#</th>
                                <th>
                                    İstifadəçi
                                    <span wire:click="sortBy('name')" class="float-right">
                                        <i
                                            class="bi bi-arrow-up {{$sortColumnName === 'name' && $sortDirection === 'asc' ? '' : 'text-muted'}}"></i>
                                        <i
                                            class="bi bi-arrow-down {{$sortColumnName === 'name' && $sortDirection === 'desc' ? '' : 'text-muted'}}"></i>
                                    </span>
                                </th>
                                <th>
                                    Email
                                    <span wire:click="sortBy('email')" class="float-right">
                                        <i
                                            class="bi bi-arrow-up {{$sortColumnName === 'email' && $sortDirection === 'asc' ? '' : 'text-muted'}}"></i>
                                        <i
                                            class="bi bi-arrow-down {{$sortColumnName === 'email' && $sortDirection === 'desc' ? '' : 'text-muted'}}"></i>
                                    </span>
                                </th>
                                <th>
                                    Status
                                    <span wire:click="sortBy('last_seen')" class="float-right">
                                        <i
                                            class="bi bi-arrow-up {{$sortColumnName === 'last_seen' && $sortDirection === 'asc' ? '' : 'text-muted'}}"></i>
                                        <i
                                            class="bi bi-arrow-down {{$sortColumnName === 'last_seen' && $sortDirection === 'desc' ? '' : 'text-muted'}}"></i>
                                    </span>
                                </th>
                                <th>
                                    görülmə
                                    <span wire:click="sortBy('last_seen')" class="float-right">
                                        <i
                                            class="bi bi-arrow-up {{$sortColumnName === 'last_seen' && $sortDirection === 'asc' ? '' : 'text-muted'}}"></i>
                                        <i
                                            class="bi bi-arrow-down {{$sortColumnName === 'last_seen' && $sortDirection === 'desc' ? '' : 'text-muted'}}"></i>
                                    </span>
                                </th>
                                <th>Əməliyyatlar</th>   
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @forelse ($users as $i=>$user)
                            <tr>
                                <td>
                                    <input type="checkbox" wire:model='selectedProducts' value="{{$user->id}}">
                                </td>
                                <td>
                                    {{$i+=1}}
                                </td>
                                <td>
                                    {{$user->name}}
                                </td>
                                <td>
                                    {{$user->email}}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center">
                                        @if (Cache::has('is_online' . $user->id))
                                        <div class="h-2.5 w-2.5 rounded-full bg-green-400 mr-2"></div> Onlayn
                                        @else
                                        <div class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2"></div> Oflayn
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($user->last_seen != null)
                                    {{ \Carbon\Carbon::parse($user->last_seen)->diffForHumans() }}
                                    @else
                                    Məlumat yoxdur
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @if(auth()->id()==$user->id)
                                        @elseif ($user->status===1)
                                        <button wire:click="edit({{$user->id}})" data-bs-target="#exampleModal"
                                            title="Redaktə et"
                                            class="focus:outline-none text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-900"
                                            data-bs-toggle="modal"><i class="bi bi-pencil-square"></i></button>
                                        <button wire:click="$emit('triggerDelete',{{ $user->id }})" title="Sil"
                                            class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900"
                                            type="button"><i class="bi bi-x-lg"></i></button>
                                        <button wire:click="block({{$user->id}})" title="Blok"
                                            class="focus:outline-none text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-900">
                                            <i class="bi bi-slash-circle"></i></i></button>
                                        @else
                                        <button wire:click="$emit('triggerDelete',{{ $user->id }})" title="Sil"
                                            class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900" >
                                           <i class="bi bi-x-lg"></i></button>
                                        <button wire:click="unblock({{$user->id}})" title="Blokdan çıxart"
                                            class="focus:outline-none text-white bg-yellow-700 hover:bg-yellow-800 focus:ring-4 focus:ring-yellow-300 font-medium rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-900"
                                            data-bs-toggle="modal"><i class="bi bi-slash-circle"></i></i></button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" align="center">
                                    Məlumat yoxdur
                                </td>
                            </tr>
                            @endforelse
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{$users->links()}}
</div>
@push('scripts')
<script>
    window.addEventListener('closeModal', event => {
        $("#exampleModal").modal('hide');
    })

    document.addEventListener('DOMContentLoaded', function () {
        @this.on('triggerDelete', id => {
            Swal.fire({
                title: 'Silmək istədiyinizdən əminsinizmi?',
                html: "Silindiyi təqdirdə geri qaytarmaq mümkün deyil",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonText: 'Ləğv et',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sil'
            }).then((result) => {
                if (result.value) {
                    @this.call('destroy', id)
                }
            });
        });
    });

    var checkedData = [];
$('#tableID').on( 'page.dt', function () {
    let allIds = localStorage.getItem("row-data-ids").split(",");

    $('.checkboxes:checkbox:checked').each(function(index, rowId){
        if($.inArray( $(this).val(),  allIds) ==  -1){
            checkedData.push($(this).val());
        }

    });
    localStorage.setItem('row-data-ids', checkedData);
} );

</script>
@endpush
