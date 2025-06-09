@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">History Perubahan Stok</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.stocks.index') }}" class="btn btn-default">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.stocks.history') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tanggal Mulai</label>
                                    <input type="date" class="form-control" name="start_date" 
                                        value="{{ request('start_date') }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tanggal Akhir</label>
                                    <input type="date" class="form-control" name="end_date" 
                                        value="{{ request('end_date') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Produk</label>
                                    <select class="form-control select2" name="product_id">
                                        <option value="">Semua Produk</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" 
                                                {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Tipe</label>
                                    <select class="form-control" name="type">
                                        <option value="">Semua Tipe</option>
                                        <option value="addition" {{ request('type') == 'addition' ? 'selected' : '' }}>
                                            Penambahan
                                        </option>
                                        <option value="reduction" {{ request('type') == 'reduction' ? 'selected' : '' }}>
                                            Pengurangan
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Produk</th>
                                    <th>Tipe</th>
                                    <th>Jumlah</th>
                                    <th>Stok Lama</th>
                                    <th>Stok Baru</th>
                                    <th>Keterangan</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($histories as $history)
                                    <tr>
                                        <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $history->product->name }}</td>
                                        <td>
                                            @if($history->type == 'addition')
                                                <span class="badge badge-success">Penambahan</span>
                                            @else
                                                <span class="badge badge-danger">Pengurangan</span>
                                            @endif
                                        </td>
                                        <td>{{ $history->quantity }}</td>
                                        <td>{{ $history->old_stock }}</td>
                                        <td>{{ $history->new_stock }}</td>
                                        <td>{{ $history->description }}</td>
                                        <td>{{ $history->user->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $histories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: 'Pilih produk...'
    });
});
</script>
@endpush
@endsection 