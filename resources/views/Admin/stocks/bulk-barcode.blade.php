<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Barcode Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .barcode-container {
            text-align: center;
            margin-bottom: 20px;
            display: inline-block;
            width: 200px;
            padding: 10px;
            box-sizing: border-box;
        }
        .barcode-container img {
            max-width: 100%;
            height: auto;
        }
        .product-name {
            font-size: 14px;
            margin: 5px 0;
        }
        .product-code {
            font-size: 12px;
            color: #666;
        }
        @media print {
            body {
                padding: 0;
            }
            .barcode-container {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    @foreach($products as $product)
        <div class="barcode-container">
            <img src="{{ route('admin.stocks.barcode', $product->id) }}" 
                alt="Barcode {{ $product->code }}">
            <div class="product-name">{{ $product->name }}</div>
            <div class="product-code">{{ $product->code }}</div>
        </div>
    @endforeach

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html> 