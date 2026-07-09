@extends('admin.Masterpage')

@section('title', 'Admin | Test Soruları')

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
                        <h4 class="card-title mb-0">Test Soruları</h4>
                        <a href="{{ route('admin.test-questions.create') }}" class="btn btn-primary">Yeni Soru</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Seviye</th>
                                        <th>Soru</th>
                                        <th>Doğru Şık</th>
                                        <th>Sıra</th>
                                        <th>Durum</th>
                                        <th class="text-end">İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($testQuestions as $testQuestion)
                                        <tr>
                                            <td>{{ $testQuestion->level?->name ?? '-' }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit($testQuestion->question, 100) }}</td>
                                            <td><span class="badge bg-light-primary text-primary">{{ $testQuestion->correct_option_key }}</span></td>
                                            <td>{{ $testQuestion->sort_order }}</td>
                                            <td>
                                                @if ($testQuestion->is_active)
                                                    <span class="badge bg-light-success text-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-light-secondary text-secondary">Pasif</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.test-questions.edit', $testQuestion) }}" class="btn btn-sm btn-outline-primary">Düzenle</a>
                                                <form action="{{ route('admin.test-questions.destroy', $testQuestion) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Bu test sorusunu silmek istediğine emin misin?')">Sil</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">Henüz test sorusu yok.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-2">
                            {{ $testQuestions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection