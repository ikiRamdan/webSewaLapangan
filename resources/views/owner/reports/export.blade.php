<table>
    <thead>
        <tr>
            <th>Customer</th>
            <th>Lapangan</th>
            <th>Jam</th>
            <th>Total</th>
            <th>Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $trx)
            @foreach($trx->details as $detail)
            <tr>
                <td>{{ $trx->customer_name }}</td>
                <td>{{ $detail->field->name }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($detail->start_time)->format('H:i') }}
                    -
                    {{ \Carbon\Carbon::parse($detail->end_time)->format('H:i') }}
                </td>
                <td>{{ $detail->subtotal ?? 0 }}</td>
                <td>{{ $trx->created_at }}</td>
            </tr>
            @endforeach
        @endforeach
    </tbody>
</table>