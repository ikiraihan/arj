<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dokumen Pendaftaran & Kwitansi Balap</title>
    <style>
        /* --- DOMPDF PERFECT RESET --- */
@page {
    margin: 5px 10px;
}
        body {
            font-family: 'Georgia', 'Times New Roman', Times, serif;
            color: #000000;
            margin: 0;
            padding: 0;
        }

        /* --- TINGGI SECTION WRAPPER DIPERPENDEK --- */
        .section-wrapper {
            width: 710px;
            border: 2px solid #000000;
            padding: 4px 8px;
            margin-bottom: 6px;
            box-sizing: border-box;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        /* --- HEADER TABLES --- */
        .table-header {
            width: 100%;
            border-collapse: collapse;
            border: 2px solid #000;
            margin-bottom: 6px;
        }
        .table-header td {
            padding: 0;
            margin: 0;
            vertical-align: top;
        }
        .title-document {
            border-right: 2px solid #000000;
            border-bottom: 2px solid #000000;
            padding: 5px 10px;
            font-weight: bold;
            font-size: 13px;
            text-transform: uppercase;
            line-height: 1.2;
            width: 550px;
        }
        .box-no-start {
            border-bottom: 2px solid #000000;
            text-align: center;
            width: 160px;
        }
        .label-no-start {
            border-bottom: 2px solid #000000;
            padding: 2px 0;
            font-weight: bold;
            font-size: 9px;
            text-transform: uppercase;
        }
        .value-no-start {
            font-size: 24px;
            font-weight: bold;
            padding: 2px 0;
            line-height: 1;
        }

        /* SCRUTINEERING HEADER */
        .title-document-scrut {
            border-right: 2px solid #000000;
            border-bottom: 2px solid #000000;
            padding: 5px 10px;
            font-weight: bold;
            font-size: 13px;
            text-transform: uppercase;
            line-height: 1.2;
            width: 220px;
        }
        .box-center-kelas {
            border-right: 2px solid #000000;
            border-bottom: 2px solid #000000;
            text-align: center;
            width: 330px;
        }

        /* --- CONTENT TABLES (DIREPATKAN) --- */
        .table-content {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3px;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }
        .table-content td {
            padding: 1px 2px;
            vertical-align: top;
        }

        /* --- FOOTER & TANDA TANGAN --- */
        .text-pernyataan {
            text-align: center;
            font-style: italic;
            font-size: 7px;
            border-bottom: 1px solid #000000;
            padding-bottom: 2px;
            margin-bottom: 3px;
            font-weight: normal;
            line-height: 1.2;
        }
        .table-footer {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .peruntukan {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            text-align: right;
            vertical-align: bottom;
        }

        /* NOMINAL RUPIAH */
        .box-nominal {
            border: 2px solid #000000;
            font-size: 16px;
            font-weight: bold;
            padding: 1px 8px;
            display: inline-block;
        }

        /* BOX LULUS SCRUT */
        .table-lurus-scrut {
            border-collapse: collapse;
            text-align: center;
            font-weight: bold;
        }
        .table-lurus-scrut td {
            border: 1px solid #000000;
            padding: 1px 8px;
            font-size: 10px;
        }

        /* FOOTER RUNNING TEXT */
        .page-absolute-footer {
            width: 710px;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 9px;
            font-weight: bold;
            margin-top: 2px;
        }

        /* --- SCALE CONTAINER --- */
        .page-container {
            width: 710px;
            transform-origin: top left;
            transform: scale(0.97);
        }
    </style>
</head>
<body>
<div class="page-container">

    @foreach($registrationClasses as $registClass)

        <table class="page-absolute-footer">
            <tr>
                <td style="width: 30%;"></td>
                <td style="width: 40%; text-align: center;">{{ $registClass->event->name ?? '-' }}</td>
                <td style="width: 30%; text-align: right;"></td>
            </tr>
        </table>

        <div class="section-wrapper">
            <table class="table-header">
                <tr>
                    <td class="title-document">Formulir Pendaftaran</td>
                    <td class="box-no-start">
                        <div class="label-no-start">No. Start</div>
                        <div class="value-no-start">{{ $registClass->registration->racer_number ?? '-' }}</div>
                    </td>
                </tr>
            </table>

            <table class="table-content">
                <tr>
                    <td>Nama Pembalap</td>
                    <td style="width: 15px;">:</td>
                    <td style="width: 300px;">{{ $registClass->registration->racer->short_name ?? '-' }}</td>
                    <td style="width: 50px; text-align: right; padding-right: 10px;">NIK</td>
                    <td style="width: 15px;">:</td>
                    <td>{{ $registClass->registration->racer->nik ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Nama Pendaftar</td><td>:</td>
                    <td colspan="4">{{ $registClass->registration->name_register ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Team</td><td>:</td>
                    <td colspan="4">{{ $registClass->registration->team_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Prov / Kota</td><td>:</td>
                    <td colspan="4">{{ $registClass->registration->racer->hometown ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Kendaraan</td><td>:</td>
                    <td colspan="4">{{ $registClass->vehicle ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Pembayaran</td><td>:</td>
                    <td colspan="4">{{ $registClass->registration->payment_method }}</td>
                </tr>
                <tr>
                    <td>Kelas</td><td>:</td>
                    <td colspan="4">{{ $registClass->eventClass->name ?? '-' }}</td>
                </tr>
            </table>

            <div class="text-pernyataan">
                Dengan ini saya menyatakan bahwa data diatas adalah benar dan sesuai dengan sebenarnya. Jika kemudian diketahui bahwa data diatas palsu/tidak sesuai dengan yang sebenarnya, maka saya sanggup menerima konsekuensi dari pihak panitia.
            </div>

            <table class="table-footer">
                <tr>
                    <td style="font-weight: bold; text-transform: uppercase;">
                        Peserta,<br><br>
                        <u>{{ $registClass->registration->racer->short_name ?? '-' }}</u>
                    </td>
                    <td class="peruntukan">Untuk Panitia</td>
                </tr>
            </table>
        </div>


        <div class="section-wrapper">
            <table class="table-header">
                <tr>
                    <td class="title-document">Kwitansi Pendaftaran</td>
                    <td class="box-no-start">
                        <div class="label-no-start">No. Start</div>
                        <div class="value-no-start">{{ $registClass->registration->racer_number ?? '-' }}</div>
                    </td>
                </tr>
            </table>

            <table class="table-content">
                <tr>
                    <td style="width: 130px;">No. Kwitansi</td>
                    <td style="width: 15px;">:</td>
                    <td>{{ $registClass->invoice_number ?? '-' }}</td>
                </tr>
                <tr>
                    <td colspan="3" style="font-weight: normal; font-size: 11px; padding: 2px 0; text-transform: none; font-style: italic;">Telah Terima dari</td>
                </tr>
                <tr>
                    <td>Nama Pembalap</td><td>:</td>
                    <td>{{ $registClass->registration->racer->short_name ?? '-' }}</td>
                </tr>
                            <tr>
                    <td>Nama Pendaftar</td><td>:</td>
                    <td colspan="4">{{ $registClass->registration->name_register ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Team</td><td>:</td>
                    <td colspan="4">{{ $registClass->registration->team_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Uang Sejumlah</td><td>:</td>
                    <td style="font-style: italic; font-weight: bold; text-transform: none;">
                        <strong>====</strong> {{ $registClass->terbilang }}  <strong>====</strong>
                    </td>
                </tr>
                <tr>
                    <td>Pembayaran</td><td>:</td>
                    <td colspan="4">{{ $registClass->registration->payment_method }}</td>
                </tr>
                <tr>
                    <td>Kelas</td><td>:</td>
                    <td>{{ $registClass->eventClass->name ?? '-' }}</td>
                </tr>
            </table>

            <table class="table-footer">
                <tr>
                    <td style="width: 250px; vertical-align: top;">
                        <div class="box-nominal">Rp {{ $registClass->price }}</div>
                        <div style="font-size: 12px; font-weight: bold; margin-top: 3px;">UNTUK PESERTA</div>
                    </td>
                    <td>&nbsp;</td>
                    <td style="width: 200px; text-align: center; font-weight: bold; text-transform: uppercase; vertical-align: top;">
                        {{ $today }}<br>
                        PENERIMA,<br>
                        <div style="margin-top: 3px; margin-bottom: 3px;">
                            <img src="{{ public_path('assets/landing/img/logo_arj.jpeg') }}" alt="Logo" style="height: 35px; width: auto;">
                        </div>
                        <div style="font-size: 11px;">Ayah Racing Jaya</div>
                    </td>
                </tr>
            </table>
        </div>


        <div class="section-wrapper">
            <table class="table-header">
                <tr>
                    <td class="title-document-scrut">Formulir Scrutineering</td>
                    <td class="box-center-kelas">
                        <div class="label-no-start">Kelas</div>
                        <div style="font-size: 14px; padding: 2px 4px; font-weight: bold;">
                            {{ $registClass->eventClass->name ?? '-' }}
                        </div>
                    </td>
                    <td class="box-no-start">
                        <div class="label-no-start">No. Start</div>
                        <div class="value-no-start">{{ $registClass->registration->racer_number ?? '-' }}</div>
                    </td>
                </tr>
            </table>

            <table class="table-content">
                <tr>
                    <td >Nama Pembalap</td>
                    <td style="width: 15px;">:</td>
                    <td colspan="4">{{ $registClass->registration->racer->short_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Nama Pendaftar</td><td>:</td>
                    <td colspan="4">{{ $registClass->registration->name_register ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Team</td><td>:</td>
                    <td colspan="4">{{ $registClass->registration->team_name ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Prov / Kota</td><td>:</td>
                    <td colspan="4">{{ $registClass->registration->racer->hometown ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Kendaraan</td><td>:</td>
                    <td colspan="4">{{ $registClass->vehicle ?? '-' }}</td>
                </tr>
                <tr>
                    <td>Noka / Nosin</td><td>:</td>
                    <td style="width: 220px;">{{ $registClass->rangka_number ?? '-' }} / {{ $registClass->vehicle_number ?? '-' }}</td>
                </tr>
            </table>

            <div class="text-pernyataan">
                Dengan menandatangani formulir ini, saya menyatakan akan menanggung semua akibat yang ditimbulkan jika ada sesuatu kejadian yang mengakibatkan kerusakan/kecelakaan atau hal lain dan tidak akan menuntut pertanggungjawaban kepada panitia penyelenggara dan pelaksana kegiatan ini.
            </div>

            <table class="table-footer">
                <tr>
                    <td style="width: 220px; font-weight: bold; text-transform: uppercase; vertical-align: top;">
                        Peserta,<br><br><br>
                        <u>{{ $registClass->registration->racer->short_name ?? '-' }}</u>
                    </td>
                    <td style="text-align: center; vertical-align: top;">
                        <table class="table-lurus-scrut" align="center">
                            <tr><td colspan="2" style="font-size: 11px; padding: 1px;">LULUS SCRUT</td></tr>
                            <tr>
                                <td>YA</td>
                                <td>TIDAK</td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 220px; text-align: right; font-weight: bold; text-transform: uppercase; vertical-align: top;">
                        SCRUTINEERING,<br><br><br>
                        <span style="border-top: 1px dashed #000000; display: inline-block; width: 140px;">&nbsp;</span>
                        <div style="font-size: 9px;">UNTUK PETUGAS SCRUT</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="section-wrapper">
            <!-- HEADER -->
            <table class="table-header">
                <tr>
                    <td class="title-document-scrut" style="width:550px;">
                        KARTU KONTROL
                    </td>

                    <td class="box-no-start">
                        <div class="label-no-start">
                            NO. START
                        </div>

                        <div class="value-no-start">
                            {{ $registClass->registration->racer_number ?? '-' }}
                        </div>
                    </td>
                </tr>
            </table>

            <!-- DATA PESERTA -->
            <table class="table-content">
                <tr>
                    <td style="width:220px;">NAMA PEMBALAP</td>
                    <td style="width:15px;">:</td>
                    <td>
                        {{ $registClass->registration->racer->short_name ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <td style="width:220px;">NAMA PENDAFTAR</td>
                    <td style="width:15px;">:</td>
                    <td>
                        {{ $registClass->registration->name_register ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <td>TEAM</td>
                    <td>:</td>
                    <td>
                        {{ $registClass->registration->team_name ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <td>KENDARAAN</td>
                    <td>:</td>
                    <td>
                        {{ $registClass->vehicle ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <td>NOKA / NOSIN</td>
                    <td>:</td>
                    <td>
                        {{ $registClass->rangka_number ?? '-' }}
                        &nbsp;&nbsp;/&nbsp;&nbsp;
                        {{ $registClass->vehicle_number ?? '-' }}
                    </td>
                </tr>

                <tr>
                    <td>KELAS</td>
                    <td>:</td>
                    <td>
                        {{ $registClass->eventClass->name ?? '-' }}
                    </td>
                </tr>
            </table>

            <!-- FOOTER -->
            <table style="width:100%; border-collapse:collapse;">
                <tr>

                    <!-- CATATAN -->
                    <td style="
                        border-top:1px solid #000;
                        padding:8px;
                        font-size:11px;
                        width:330px;
                        vertical-align:top;
                        text-transform:none;
                        font-weight:bold;
                    ">
                        *Kehilangan kartu kontrol akan menerima sanksi sesuai dengan
                        peraturan perlombaan
                    </td>

                    <!-- RACE 1 -->
                    <td style="
                        border:1px solid #000;
                        width:120px;
                        padding:0;
                        text-align:center;
                    ">
                        <div style="
                            border-bottom:1px solid #000;
                            padding:4px;
                            font-size:14px;
                            font-weight:bold;
                        ">
                            RACE 1
                        </div>

                        <div style="height:45px;"></div>
                    </td>

                    <!-- RACE 2 -->
                    <td style="
                        border:1px solid #000;
                        width:120px;
                        padding:0;
                        text-align:center;
                    ">
                        <div style="
                            border-bottom:1px solid #000;
                            padding:4px;
                            font-size:14px;
                            font-weight:bold;
                        ">
                            RACE 2
                        </div>

                        <div style="height:45px;"></div>
                    </td>

                    <!-- FINAL -->
                    <td style="
                        border:1px solid #000;
                        width:120px;
                        padding:0;
                        text-align:center;
                    ">
                        <div style="
                            border-bottom:1px solid #000;
                            padding:4px;
                            font-size:14px;
                            font-weight:bold;
                        ">
                            FINAL
                        </div>

                        <div style="height:45px;"></div>
                    </td>

                </tr>
            </table>

        </div>

        <table class="page-absolute-footer">
            <tr>
                <td style="width: 30%;">PRINT: {{ now()->format('d/m/Y H:i') }}</td>
                <td style="width: 40%; text-align: center;">{{ $registClass->event->name ?? 'JRS KEJURPROV 2026' }}</td>
                <td style="width: 30%; text-align: right;">AYAH RACING JAYA ORGANIZER</td>
            </tr>
        </table>
        @if(!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif

    @endforeach
</div>
</body>
</html>
