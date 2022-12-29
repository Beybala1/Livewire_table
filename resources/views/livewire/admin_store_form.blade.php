<button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
    data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Daxil et </button>
<form>
    <div wire:ignore.self class="modal fade" class="modal fade" id="exampleModal" tabindex="-1"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">İstifadəçi əlavə edin</h5>
                    <!-- component -->
                    <button wire:click.prevent="cancel" data-bs-dismiss="modal" type="button"
                        class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                        <span class="sr-only">Close menu</span>
                        <!-- Heroicon name: outline/x -->
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="mb-3">
                            <label for="recipient-name" class="col-form-label">İstifadəçi adı:</label>
                            <input wire:model="name" type="text"
                                class="form-control @error('name') is-invalid @enderror" id="recipient-name">
                            @error('name') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="recipient-email" class="col-form-label">Email:</label>
                            <input wire:model="email" type="email"
                                class="form-control @error('email') is-invalid @enderror" id="recipient-email">
                            @error('email') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="recipient-password" class="col-form-label">Parol:</label>
                            <input wire:model="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" id="recipient-password">
                            @error('password') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                        <div class="mb-3">
                            <label for="recipient-password_confirmation" class="col-form-label">Təkrar parol:</label>
                            <input wire:model="password_confirmation" type="password"
                                class="form-control @error('password_confirmation') is-invalid @enderror" id="recipient-password_confirmation">
                            @error('password_confirmation') <span class="text-danger">{{ $message }}</span>@enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button wire:click.prevent="cancel" type="button"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                        data-bs-dismiss="modal">Ləğv et</button>
                    <button wire:click.prevent="store" type="button"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Daxil et</button>
                </div>
            </div>
        </div>
    </div>
</form>

