@extends('layouts.master', ['step' => 2]) 

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Dokumen & Pilihan Departemen</h2>
    <p class="text-sm text-gray-500 mt-1">Unggah dokumen yang diperlukan dan pilih maksimal 2 departemen untuk dilamar.</p>
</div>

<form action="{{ route('register.step2') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    <div class="p-6 border border-gray-200 rounded-xl bg-gray-50">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Pilihan Pertama <span class="text-red-500">*</span></h3>
        
        <div class="mb-4">
            <label for="pilihan_1" class="block text-sm font-medium text-gray-700">Departemen</label>
            <select name="pilihan_1" id="pilihan_1" required onchange="checkPortfolio(1)"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2.5 bg-white">
                <option value="">-- Pilih Departemen --</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" 
                        {{ old('pilihan_1', $data['pilihan_1'] ?? '') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
            </select>
            @error('pilihan_1') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div id="portfolio_section_1" class="hidden mt-4 pt-4 border-t border-gray-200">
            <label for="portofolio_1" class="block text-sm font-medium text-gray-700 mb-2">Link Portofolio Pilihan 1 <span class="text-red-500">*</span></label>
            
            <input type="url" name="portofolio_1" id="portofolio_1" 
                value="{{ old('portofolio_1', $data['portofolio_1'] ?? '') }}"
                placeholder="https://drive.google.com/drive/folders/..."
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2.5 bg-white">
            
            <p class="mt-1 text-xs text-gray-500">Pastikan akses link Google Drive sudah diatur ke **"Anyone with the link" (Siapa saja yang memiliki link)**.</p>
            @error('portofolio_1') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="p-6 border border-gray-200 rounded-xl bg-gray-50">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Pilihan Kedua (Opsional)</h3>
        
        <div class="mb-4">
            <label for="pilihan_2" class="block text-sm font-medium text-gray-700">Departemen</label>
            <select name="pilihan_2" id="pilihan_2" onchange="checkPortfolio(2)"
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2.5 bg-white">
                <option value="">-- Tidak Memilih --</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" 
                        {{ old('pilihan_2', $data['pilihan_2'] ?? '') == $dept->id ? 'selected' : '' }}>
                        {{ $dept->name }}
                    </option>
                @endforeach
            </select>
            @error('pilihan_2') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>

        <div id="portfolio_section_2" class="hidden mt-4 pt-4 border-t border-gray-200">
            <label for="portofolio_2" class="block text-sm font-medium text-gray-700 mb-2">Link Portofolio Pilihan 2 <span class="text-red-500">*</span></label>
            
            <input type="url" name="portofolio_2" id="portofolio_2" 
                value="{{ old('portofolio_2', $data['portofolio_2'] ?? '') }}"
                placeholder="https://drive.google.com/drive/folders/..."
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm border p-2.5 bg-white">
            
            <p class="mt-1 text-xs text-gray-500">Pastikan akses link Google Drive sudah diatur ke **"Anyone with the link" (Siapa saja yang memiliki link)**.</p>
            @error('portofolio_2') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
        </div>
    </div>

    <div class="flex justify-between pt-4 border-t border-gray-200">
        <a href="{{ route('register.step1') }}" class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-6 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            &laquo; Kembali
        </a>
        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 py-2 px-6 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
            Selanjutnya &raquo;
        </button>
    </div>
</form>

<script>
    // Script untuk mengecek pilihan departemen (yang sudah kamu punya)
    const departments = @json($departments);

    function checkPortfolio(choiceNumber) {
        const selectElement = document.getElementById(`pilihan_${choiceNumber}`);
        const portfolioSection = document.getElementById(`portfolio_section_${choiceNumber}`);
        const selectedDeptId = selectElement.value;

        if (!selectedDeptId) {
            portfolioSection.classList.add('hidden');
            return;
        }

        const selectedDept = departments.find(dept => dept.id == selectedDeptId);

        if (selectedDept && selectedDept.requires_portfolio) {
            portfolioSection.classList.remove('hidden');
        } else {
            portfolioSection.classList.add('hidden');
        }
    }

    window.onload = function() {
        checkPortfolio(1);
        checkPortfolio(2);
    };

    // TAMBAHAN BARU: Validasi Ukuran File Sebelum Submit
    document.addEventListener('DOMContentLoaded', function() {
        const maxSizeInBytes = 10 * 1024 * 1024; // 10 MB
        const fileInputs = [document.getElementById('portofolio_1'), document.getElementById('portofolio_2')];

        fileInputs.forEach(input => {
            if(input) {
                input.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        if (this.files[0].size > maxSizeInBytes) {
                            alert('Ukuran file ' + this.files[0].name + ' terlalu besar! Maksimal 10 MB.');
                            this.value = ''; // Kosongkan file yang dipilih agar tidak error saat disubmit
                        }
                    }
                });
            }
        });
    });
</script>
@endsection