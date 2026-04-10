@extends('admin.Masterpage')

@section('title', 'Admin | Gunun Zikri')

@section('content')
    <section>
        @if (session('status'))
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success" role="alert">
                        <div class="alert-body">{{ session('status') }}</div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Gunun Zikri Yonetimi</h4>
                        <a href="{{ route('admin.daily-zikrs.create') }}" class="btn btn-primary">Yeni Gunluk Zikir</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Tarih</th>
                                        <th>Dil</th>
                                        <th>Baslik</th>
                                        <th>Zikir</th>
                                        <th>Adet</th>
                                        <th>Durum</th>
                                        <th class="text-end">Islem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($dailyZikrs as $dailyZikr)
                                        <tr>
                                            <td>{{ $dailyZikr->date?->format('Y-m-d') ?? '-' }}</td>
                                            <td>{{ $dailyZikr->locale ?? '-' }}</td>
                                            <td>{{ $dailyZikr->title }}</td>
                                            <td>{{ $dailyZikr->zikir?->zikir ?? '-' }}</td>
                                            <td>{{ $dailyZikr->count_suggestion ?? '-' }}</td>
                                            <td>
                                                @if ($dailyZikr->is_active)
                                                    <span class="badge rounded-pill bg-light-success">Aktif</span>
                                                @else
                                                    <span class="badge rounded-pill bg-light-secondary">Pasif</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.daily-zikrs.edit', $dailyZikr) }}" class="btn btn-sm btn-outline-primary">Duzenle</a>
                                                <form action="{{ route('admin.daily-zikrs.destroy', $dailyZikr) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu gunluk zikri silmek istedigine emin misin?')">Sil</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">Henuz gunluk zikir kaydi yok.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-2">
                            {{ $dailyZikrs->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
