<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pemasok Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.suppliers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Informasi Dasar -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">Informasi Dasar</h3>
                                
                                <div>
                                    <x-input-label for="nama" value="Nama" />
                                    <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="old('nama')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('nama')" />
                                </div>

                                <div>
                                    <x-input-label for="email" value="Email" />
                                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                </div>

                                <div>
                                    <x-input-label for="telepon" value="Telepon" />
                                    <x-text-input id="telepon" name="telepon" type="text" class="mt-1 block w-full" :value="old('telepon')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('telepon')" />
                                </div>

                                <div>
                                    <x-input-label for="alamat" value="Alamat" />
                                    <textarea id="alamat" name="alamat" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3" required>{{ old('alamat') }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
                                </div>
                            </div>

                            <!-- Informasi Toko dan Bank -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium text-gray-900">Informasi Toko dan Bank</h3>

                                <div>
                                    <x-input-label for="nama_toko" value="Nama Toko" />
                                    <x-text-input id="nama_toko" name="nama_toko" type="text" class="mt-1 block w-full" :value="old('nama_toko')" required />
                                    <x-input-error class="mt-2" :messages="$errors->get('nama_toko')" />
                                </div>

                                <div>
                                    <x-input-label for="jenis" value="Jenis" />
                                    <select id="jenis" name="jenis" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                        <option value="">Pilih Jenis</option>
                                        <option value="retail" {{ old('jenis') == 'retail' ? 'selected' : '' }}>Retail</option>
                                        <option value="grosir" {{ old('jenis') == 'grosir' ? 'selected' : '' }}>Grosir</option>
                                        <option value="distributor" {{ old('jenis') == 'distributor' ? 'selected' : '' }}>Distributor</option>
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('jenis')" />
                                </div>

                                <div>
                                    <x-input-label for="nama_bank" value="Nama Bank" />
                                    <x-text-input id="nama_bank" name="nama_bank" type="text" class="mt-1 block w-full" :value="old('nama_bank')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('nama_bank')" />
                                </div>

                                <div>
                                    <x-input-label for="pemegang_rekening" value="Pemegang Rekening" />
                                    <x-text-input id="pemegang_rekening" name="pemegang_rekening" type="text" class="mt-1 block w-full" :value="old('pemegang_rekening')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('pemegang_rekening')" />
                                </div>

                                <div>
                                    <x-input-label for="nomor_rekening" value="Nomor Rekening" />
                                    <x-text-input id="nomor_rekening" name="nomor_rekening" type="text" class="mt-1 block w-full" :value="old('nomor_rekening')" />
                                    <x-input-error class="mt-2" :messages="$errors->get('nomor_rekening')" />
                                </div>

                                <div>
                                    <x-input-label for="foto" value="Foto" />
                                    <input type="file" id="foto" name="foto" class="mt-1 block w-full" accept="image/*">
                                    <x-input-error class="mt-2" :messages="$errors->get('foto')" />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('admin.suppliers.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 