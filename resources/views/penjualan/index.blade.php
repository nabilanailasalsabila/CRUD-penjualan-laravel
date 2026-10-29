
@extends('layouts.app')

@section('title', 'Daftar Penjualan')

@section('content')
<div class="topbar">
    <div>
        <h1>Penjualan</h1>
        <p class="muted">Kelola transaksi penjualan sederhana.</p>
    </div>

    <a class="btn btn-primary" href="{{ route('penjualan.create') }}">
        + Tambah Transaksi
    </a>
</div>

{{-- Ringkasan omzet --}}
<div class="card">
    <div class="muted">Total omzet transaksi selesai</div>
    <div class="metric">
        Rp {{ number_format((float) $totalOmzet, 0, ',', '.') }}
    </div>
</div>

{{-- Pencarian dan filter status --}}
<div class="card">
    <h3> Cari dan Filter Transaksi</h3>

    <form action="{{ route('penjualan.index') }}" method="GET">
        <div class="form-grid">
            <div class="form-group">
                <label for="search">Pencarian</label>
                <input
                    type="text"
                    id="search"
                    name="search"
                    value="{{ $search ?? request('search') }}"
                    placeholder="Kode, nama pelanggan, atau produk"
                >
            </div>

            <div class="form-group">
                <label for="status">Status Transaksi</label>
                <select id="status" name="status">
                    <option value="">Semua Status</option>
                    <option
                        value="Selesai"
                        {{ ($status ?? request('status')) === 'Selesai' ? 'selected' : '' }}
                    >
                        Selesai
                    </option>
                    <option
                        value="Pending"
                        {{ ($status ?? request('status')) === 'Pending' ? 'selected' : '' }}
                    >
                        Pending
                    </option>
                    <option
                        value="Batal"
                        {{ ($status ?? request('status')) === 'Batal' ? 'selected' : '' }}
                    >
                        Batal
                    </option>
                </select>
            </div>
        </div>

        <div class="actions" style="margin-top: 16px;">
            <button type="submit" class="btn btn-primary">
                Cari Transaksi
            </button>

            <a href="{{ route('penjualan.index') }}" class="btn">
                Reset
            </a>
        </div>
    </form>
</div>

{{-- Tabel daftar transaksi --}}
<div class="card">
    <div style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Produk</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($penjualan as $item)
                    <tr>
                        <td>{{ $item->kode_transaksi }}</td>
                        <td>{{ $item->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $item->nama_pelanggan }}</td>
                        <td>{{ $item->nama_produk }}</td>
                        <td>
                            Rp {{ number_format((float) $item->total, 0, ',', '.') }}
                        </td>
                        <td>
                            <span class="badge">{{ $item->status }}</span>
                        </td>
                        <td>
                            <div class="actions">
                                <a
                                    class="btn"
                                    href="{{ route('penjualan.show', $item) }}"
                                >
                                    Detail
                                </a>

                                <a
                                    class="btn"
                                    href="{{ route('penjualan.edit', $item) }}"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route('penjualan.destroy', $item) }}"
                                    method="POST"
                                    onsubmit="return confirm('Hapus transaksi ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger" type="submit">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="muted">
                            @if (request()->filled('search') || request()->filled('status'))
                                Tidak ada transaksi yang sesuai dengan pencarian atau filter.
                            @else
                                Belum ada transaksi.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
