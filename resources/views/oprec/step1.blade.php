@extends('layouts.master', ['step' => 1])

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Data Diri</h2>
    <p class="text-sm text-gray-500 mt-1">Lengkapi informasi pribadi kamu di bawah ini dengan data yang valid.</p>
</div>

<form action="{{ route('register.step1') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label for="nama_lengkap" class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="nama_lengkap" id="nama_lengkap" required 
                value="{{ old('nama_lengkap', $data['nama_lengkap'] ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2.5">
            @error('nama_lengkap') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="nrp" class="block text-sm font-medium text-gray-700">NRP <span class="text-red-500">*</span></label>
            <input type="text" name="nrp" id="nrp" required placeholder="Contoh: c142xxxxx"
                value="{{ old('nrp', $data['nrp'] ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2.5">
            @error('nrp') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="program_studi" class="block text-sm font-medium text-gray-700">Program Studi <span class="text-red-500">*</span></label>
            <input type="text" name="program_studi" id="program_studi" required 
                value="{{ old('program_studi', $data['program_studi'] ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2.5">
            @error('program_studi') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="agama" class="block text-sm font-medium text-gray-700">Agama <span class="text-red-500">*</span></label>
            <select name="agama" id="agama" required 
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2.5 bg-white">
                <option value="">Pilih Agama</option>
                @php $agamas = ['Kristen Protestan', 'Katolik', 'Islam', 'Hindu', 'Buddha', 'Konghucu', 'Lainnya']; @endphp
                @foreach($agamas as $agama)
                    <option value="{{ $agama }}" {{ old('agama', $data['agama'] ?? '') == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                @endforeach
            </select>
            @error('agama') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir <span class="text-red-500">*</span></label>
            <input type="text" name="tempat_lahir" id="tempat_lahir" required 
                value="{{ old('tempat_lahir', $data['tempat_lahir'] ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2.5">
            @error('tempat_lahir') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tanggal Lahir <span class="text-red-500">*</span></label>
            <input type="date" name="tanggal_lahir" id="tanggal_lahir" required 
                value="{{ old('tanggal_lahir', $data['tanggal_lahir'] ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2.5">
            @error('tanggal_lahir') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>
        
        <div>
            <label for="no_telepon" class="block text-sm font-medium text-gray-700">Nomor Telepon / WA <span class="text-red-500">*</span></label>
            <input type="text" name="no_telepon" id="no_telepon" required placeholder="08xxxxxxxxxx"
                value="{{ old('no_telepon', $data['no_telepon'] ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2.5">
            @error('no_telepon') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="id_line" class="block text-sm font-medium text-gray-700">ID Line</label>
            <input type="text" name="id_line" id="id_line" 
                value="{{ old('id_line', $data['id_line'] ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2.5">
        </div>

        <div>
            <label for="instagram" class="block text-sm font-medium text-gray-700">Username Instagram</label>
            <input type="text" name="instagram" id="instagram" placeholder="@username"
                value="{{ old('instagram', $data['instagram'] ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2.5">
        </div>
        
        <div>
            <label for="ipk" class="block text-sm font-medium text-gray-700">IPK Terakhir <span class="text-red-500">*</span></label>
            <input type="number" step="0.01" min="0" max="4" name="ipk" id="ipk" required placeholder="Contoh: 3.75"
                value="{{ old('ipk', $data['ipk'] ?? '') }}"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2.5">
            @error('ipk') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>
    </div>

    <div>
        <label for="alamat_domisili" class="block text-sm font-medium text-gray-700">Alamat Domisili <span class="text-red-500">*</span></label>
        <textarea name="alamat_domisili" id="alamat_domisili" rows="2" required placeholder="Alamat kos/tinggal saat ini"
            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2.5">{{ old('alamat_domisili', $data['alamat_domisili'] ?? '') }}</textarea>
        @error('alamat_domisili') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
    </div>

    <div class="mt-8 border border-gray-200 shadow-sm rounded-lg p-6 bg-white">
        <h3 class="text-lg font-bold text-gray-800 mb-1">Dokumen Persyaratan</h3>
        <p class="text-xs text-gray-500 mb-6">Format: PDF, JPG, PNG. Maksimal ukuran per file: <span class="font-bold text-red-500">10MB</span>.</p>
        
        <div class="space-y-6">
            <div>
                <label for="jadwal_hidup" class="block text-sm font-medium text-gray-700 mb-1">
                    1. Jadwal Hidup / CV
                    @if(isset($activeBatch) && $activeBatch->requires_cv)
                        <span class="text-red-500 font-bold">* (Wajib)</span>
                    @else
                        <span class="text-gray-400 font-normal">(Opsional)</span>
                    @endif
                </label>
                <input type="file" name="jadwal_hidup" id="jadwal_hidup" accept=".pdf,.jpg,.jpeg,.png"
                    {{ (isset($activeBatch) && $activeBatch->requires_cv && empty($data['jadwal_hidup_path'])) ? 'required' : '' }}
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-200 rounded-md">
                @if(!empty($data['jadwal_hidup_path'])) 
                    <p class="text-xs text-green-600 mt-2 font-medium">✓ File Jadwal Hidup sudah tersimpan.</p> 
                @endif
                @error('jadwal_hidup') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="ktm" class="block text-sm font-medium text-gray-700 mb-1">
                    2. Scan / Foto KTM <span class="text-red-500 font-bold">* (Wajib)</span>
                </label>
                <input type="file" name="ktm" id="ktm" accept=".pdf,.jpg,.jpeg,.png"
                    {{ empty($data['ktm_path']) ? 'required' : '' }}
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-200 rounded-md">
                @if(!empty($data['ktm_path'])) 
                    <p class="text-xs text-green-600 mt-2 font-medium">✓ File KTM sudah tersimpan.</p> 
                @endif
                @error('ktm') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="pas_foto" class="block text-sm font-medium text-gray-700 mb-1">
                    3. Pas Foto 3x4 (Bebas Rapi) <span class="text-red-500 font-bold">* (Wajib)</span>
                </label>
                <input type="file" name="pas_foto" id="pas_foto" accept=".jpg,.jpeg,.png"
                    {{ empty($data['pas_foto_path']) ? 'required' : '' }}
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-200 rounded-md">
                @if(!empty($data['pas_foto_path'])) 
                    <p class="text-xs text-green-600 mt-2 font-medium">✓ Pas Foto sudah tersimpan.</p> 
                @endif
                @error('pas_foto') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="transkrip" class="block text-sm font-medium text-gray-700 mb-1">
                    4. Transkrip KHS Terakhir <span class="text-red-500 font-bold">* (Wajib)</span>
                </label>
                <input type="file" name="transkrip" id="transkrip" accept=".pdf,.jpg,.jpeg,.png"
                    {{ empty($data['transkrip_path']) ? 'required' : '' }}
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-200 rounded-md">
                @if(!empty($data['transkrip_path'])) 
                    <p class="text-xs text-green-600 mt-2 font-medium">✓ File Transkrip KHS sudah tersimpan.</p> 
                @endif
                @error('transkrip') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="skkk" class="block text-sm font-medium text-gray-700 mb-1">
                    5. Bukti Poin SKKK <span class="text-red-500 font-bold">* (Wajib)</span>
                </label>
                <input type="file" name="skkk" id="skkk" accept=".pdf,.jpg,.jpeg,.png"
                    {{ empty($data['skkk_path']) ? 'required' : '' }}
                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-200 rounded-md">
                @if(!empty($data['skkk_path'])) 
                    <p class="text-xs text-green-600 mt-2 font-medium">✓ Bukti SKKK sudah tersimpan.</p> 
                @endif
                @error('skkk') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="flex justify-end pt-6">
        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 py-3 px-8 text-sm font-bold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            Simpan & Selanjutnya &raquo;
        </button>
    </div>
</form>
@endsection