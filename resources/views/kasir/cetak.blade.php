<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Pesanan</title>
    <style>
        body {
            font-family: monospace;
            font-size: 12px;
            max-width: 250px;
            margin: auto;
        }
        .center {
            text-align: center;
        }
        .bold {
            font-weight: bold;
        }
        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }
        .item {
            display: flex;
            justify-content: space-between;
        }
        .item-name {
            flex: 1;
        }
        .item-total {
            text-align: right;
            min-width: 50px;
        }
        .footer {
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="center bold">CELIBI BAKERY</div>
    <div class="center">Jl. Contoh Alamat No.123</div>
    <div class="center">0812-3456-7890</div>
    <div class="line"></div>

    <div><b>Tanggal:</b> {{ \Carbon\Carbon::parse($pesanan->created_at)->format('d/m/Y H:i') }}</div>
    <div><b>No. Meja:</b> {{ $pesanan->no_meja }}</div>
    <div><b>Nama:</b> {{ $pesanan->nama_pelanggan }}</div>
    <div class="line"></div>

    <div class="bold">Pesanan:</div>
    @foreach($detail as $menuId => $qty)
        @php $menu = $menus[$menuId]; @endphp
        <div class="item">
            <div class="item-name">{{ $menu->nama }} x{{ $qty }}</div>
            <div class="item-total">Rp{{ number_format($menu->harga * $qty, 0, ',', '.') }}</div>
        </div>
    @endforeach
    <div class="line"></div>

    <div class="item bold">
        <div>Total</div>
        <div class="item-total">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
    </div>

    <div class="line"></div>
    <div class="center">Terima Kasih</div>
    <div class="center">~ Celibi Bakery ~</div>

</body>
</html>