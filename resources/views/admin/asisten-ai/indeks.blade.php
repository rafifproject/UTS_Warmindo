@extends('layouts.admin')

@section('title', 'AI Business Assistant — Warmindo Owner')
@section('page-title', 'AI Business Assistant')

@section('content')
<div class="ai-assistant-page">
    {{-- Screen 40: AI Assistant Hero & Prompt Section --}}
    <div class="card" style="background: linear-gradient(135deg, #164032 0%, #1e5543 100%); color: #fff; padding: 2rem; border-radius: 16px; margin-bottom: 2rem; box-shadow: 0 10px 25px rgba(22,64,50,0.15);">
        <div style="display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;">
            <div style="flex-shrink: 0; background: rgba(255,255,255,0.1); border-radius: 50%; padding: 1.25rem; display: flex; align-items: center; justify-content: center; border: 2px solid rgba(255,255,255,0.2);">
                <img src="{{ asset('images/ai-bot.svg') }}" alt="AI Bot" style="width: 80px; height: 80px;">
            </div>
            <div style="flex: 1; min-width: 280px;">
                <div style="display: inline-block; background: rgba(240, 101, 36, 0.25); color: #FFA07A; font-weight: 700; font-size: 0.8rem; padding: 0.25rem 0.75rem; border-radius: 20px; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid rgba(240, 101, 36, 0.4);">
                    Enterprise Business Intelligence
                </div>
                <h2 style="font-size: 1.75rem; font-weight: 800; margin-bottom: 0.4rem; color: #fff;">Halo! Saya AI Business Assistant Warmindo</h2>
                <p style="color: rgba(255,255,255,0.85); font-size: 0.95rem; line-height: 1.5; margin: 0;">
                    Siap membantu Anda menganalisis performa penjualan, evaluasi stok bahan baku, tren pesanan pelanggan, dan memberikan saran rekomendasi strategis.
                </p>
            </div>
        </div>

        {{-- Interactive Prompt Box --}}
        <div style="margin-top: 1.75rem; background: rgba(255,255,255,0.08); padding: 1rem; border-radius: 12px; border: 1px solid rgba(255,255,255,0.15);">
            <form action="{{ route('admin.ai.analyze') }}" method="POST" style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                @csrf
                <div style="flex: 1; min-width: 250px; position: relative;">
                    <input type="text" name="prompt" id="ai-prompt-input" 
                        placeholder="Tanyakan sesuatu tentang bisnis Anda (contoh: Analisis tren penjualan & stok menipis)..." 
                        value="{{ $question ?? '' }}"
                        style="width: 100%; padding: 0.85rem 1rem; border-radius: 8px; border: 1px solid rgba(255,255,255,0.3); background: #ffffff; color: #1e293b; font-size: 0.95rem; outline: none; font-weight: 500;">
                </div>
                <button type="submit" class="btn" style="background: var(--primary); color: #fff; font-weight: 700; padding: 0.85rem 1.75rem; border-radius: 8px; display: inline-flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 12px rgba(240, 101, 36, 0.4);">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Analisis dengan AI
                </button>
            </form>

            {{-- Quick Chips / Quick Prompts --}}
            <div style="margin-top: 0.85rem; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                <span style="font-size: 0.8rem; color: rgba(255,255,255,0.7); font-weight: 600;">Saran Pertanyaan:</span>
                <button type="button" class="quick-chip" onclick="setPrompt('Bagaimana evaluasi tren penjualan dan omzet minggu ini?')">
                    📈 Tren Penjualan
                </button>
                <button type="button" class="quick-chip" onclick="setPrompt('Menu apa yang paling laris dan bagaimana ketersediaan stoknya?')">
                    🍜 Menu Terlaris & Stok
                </button>
                <button type="button" class="quick-chip" onclick="setPrompt('Bandingkan rasio pesanan Dine In vs Take Away dan berikan strategi optimasinya.')">
                    🍽️ Dine In vs Take Away
                </button>
                <button type="button" class="quick-chip" onclick="setPrompt('Berikan 3 rekomendasi strategi promosi terbaik untuk meningkatkan profit Warmindo.')">
                    💡 Strategi Omzet
                </button>
            </div>
        </div>
    </div>

    {{-- Consultation Response Section (If Prompt Was Submitted) --}}
    @if(isset($aiResponse))
        <div class="card" style="margin-bottom: 2rem; border-left: 4px solid var(--primary); background: #fff;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(240,101,36,0.1); display: flex; align-items: center; justify-content: center; color: var(--primary);">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                    <div>
                        <h4 style="margin: 0; font-size: 1.05rem; font-weight: 700; color: #1e293b;">Konsultasi Bisnis Owner</h4>
                        <span style="font-size: 0.8rem; color: #64748b;">Pertanyaan: "<em>{{ $question }}</em>"</span>
                    </div>
                </div>
                <span class="badge badge-success" style="display: inline-flex; align-items: center; gap: 0.35rem;">
                    <span style="width: 6px; height: 6px; border-radius: 50%; background: currentColor;"></span> Data Terverifikasi
                </span>
            </div>
            <div style="line-height: 1.7; color: #334155; font-size: 0.95rem; white-space: pre-line; background: #f8fafc; padding: 1.25rem; border-radius: 8px;">
{!! nl2br(e($aiResponse)) !!}
            </div>
        </div>
    @endif

    {{-- Screen 41: Detail Analisis, Data Pendukung, dan Rekomendasi --}}
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
        {{-- Left: Metrics Summary & Automated Analysis --}}
        <div>
            {{-- Data Pendukung Grid --}}
            <div class="card" style="margin-bottom: 1.5rem;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="20" height="20" fill="none" stroke="var(--primary)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Data Pendukung Analisis
                    </h3>
                    <span class="badge badge-info">{{ $metrics['period'] }}</span>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;">
                    <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid var(--border-color);">
                        <span style="font-size: 0.8rem; color: #64748b; font-weight: 600; display: block; margin-bottom: 0.25rem;">Omzet Periode Ini</span>
                        <span style="font-size: 1.35rem; font-weight: 800; color: var(--primary);">{{ $metrics['omzet_mingguan'] }}</span>
                    </div>
                    <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid var(--border-color);">
                        <span style="font-size: 0.8rem; color: #64748b; font-weight: 600; display: block; margin-bottom: 0.25rem;">Total Transaksi Berhasil</span>
                        <span style="font-size: 1.35rem; font-weight: 800; color: #1e293b;">{{ $metrics['total_transaksi'] }} Transaksi</span>
                    </div>
                    <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid var(--border-color);">
                        <span style="font-size: 0.8rem; color: #64748b; font-weight: 600; display: block; margin-bottom: 0.25rem;">Rata-rata Nilai Belanja</span>
                        <span style="font-size: 1.35rem; font-weight: 800; color: #0284c7;">{{ $metrics['rata_rata_transaksi'] }}</span>
                    </div>
                    <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid var(--border-color);">
                        <span style="font-size: 0.8rem; color: #64748b; font-weight: 600; display: block; margin-bottom: 0.25rem;">Menu Primadona</span>
                        <span style="font-size: 1.05rem; font-weight: 700; color: #164032;">{{ $metrics['menu_terlaris'] }}</span>
                    </div>
                    <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid var(--border-color);">
                        <span style="font-size: 0.8rem; color: #64748b; font-weight: 600; display: block; margin-bottom: 0.25rem;">Status Stok Bahan</span>
                        @if($metrics['jumlah_stok_menipis'] > 0)
                            <span style="font-size: 1.05rem; font-weight: 700; color: #dc2626;">{{ $metrics['jumlah_stok_menipis'] }} Menipis</span>
                        @else
                            <span style="font-size: 1.05rem; font-weight: 700; color: #16a34a;">Semua Aman</span>
                        @endif
                    </div>
                    <div style="background: #f8fafc; padding: 1rem; border-radius: 8px; border: 1px solid var(--border-color);">
                        <span style="font-size: 0.8rem; color: #64748b; font-weight: 600; display: block; margin-bottom: 0.25rem;">Rasio Layanan</span>
                        <span style="font-size: 0.95rem; font-weight: 700; color: #475569;">{{ $metrics['rasio_layanan'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Ringkasan & Kelebihan Operasional --}}
            <div class="card">
                <h3 style="font-size: 1.15rem; font-weight: 700; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Evaluasi Komprehensif
                </h3>
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 1rem; margin-bottom: 1rem;">
                    <p style="margin: 0; color: #166534; font-size: 0.95rem; line-height: 1.6;">
                        <strong>Ringkasan Eksekutif:</strong> {{ $autoAnalysis['ringkasan'] }}
                    </p>
                </div>

                <div style="margin-bottom: 1.25rem;">
                    <h5 style="font-size: 0.95rem; font-weight: 700; color: #164032; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                        <span style="color: #16a34a;">●</span> Faktor Kekuatan & Keberhasilan
                    </h5>
                    <ul style="margin: 0; padding-left: 1.25rem; color: #334155; font-size: 0.9rem; line-height: 1.6;">
                        @foreach($autoAnalysis['kelebihan'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h5 style="font-size: 0.95rem; font-weight: 700; color: #991b1b; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.4rem;">
                        <span style="color: #dc2626;">●</span> Area Perhatian & Mitigasi Risiko
                    </h5>
                    <ul style="margin: 0; padding-left: 1.25rem; color: #334155; font-size: 0.9rem; line-height: 1.6;">
                        @foreach($autoAnalysis['perhatian'] as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        {{-- Right: Rekomendasi Strategis --}}
        <div>
            <div class="card" style="border-top: 4px solid var(--primary); height: 100%;">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem;">
                    <h3 style="font-size: 1.15rem; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="20" height="20" fill="none" stroke="var(--primary)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Rekomendasi Aksi
                    </h3>
                </div>

                <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.25rem;">
                    Langkah nyata berbasis data untuk meningkatkan profitabilitas, efisiensi operasional, dan kepuasan pelanggan:
                </p>

                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    @foreach($autoAnalysis['rekomendasi'] as $index => $rec)
                        <div style="display: flex; gap: 0.75rem; background: #fffaf5; border: 1px solid #fed7aa; padding: 1rem; border-radius: 8px;">
                            <div style="width: 28px; height: 28px; border-radius: 50%; background: var(--primary); color: #fff; font-weight: 800; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                {{ $index + 1 }}
                            </div>
                            <div style="font-size: 0.9rem; color: #7c2d12; line-height: 1.5; font-weight: 500;">
                                {{ $rec }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="margin-top: 2rem; padding: 1rem; background: #f8fafc; border-radius: 8px; border: 1px dashed var(--border-color); text-align: center;">
                    <p style="font-size: 0.8rem; color: #64748b; margin-bottom: 0.75rem;">
                        Ingin rekomendasi lebih spesifik per produk?
                    </p>
                    <a href="{{ route('admin.reports.top-menus') }}" class="btn btn-outline" style="font-size: 0.85rem; width: 100%; justify-content: center;">
                        Buka Laporan Menu Terlaris
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.quick-chip {
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff;
    padding: 0.35rem 0.75rem;
    border-radius: 20px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.2s ease;
    font-weight: 500;
}
.quick-chip:hover {
    background: rgba(255,255,255,0.3);
    transform: translateY(-1px);
}
</style>

<script>
function setPrompt(text) {
    const input = document.getElementById('ai-prompt-input');
    input.value = text;
    input.focus();
}
</script>
@endsection
