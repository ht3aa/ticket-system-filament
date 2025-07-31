<div>
    <table class="w-full border-collapse border border-gray-300">
        <tbody>
            <tr class="border-b border-gray-300">
                <td class="p-2 font-bold">الزبون:</td>
                <td class="p-2">{{ $record->client->name }}</td>
            </tr>
            <tr class="border-b border-gray-300">
                <td class="p-2 font-bold">الماركة:</td>
                <td class="p-2">{{ $record->make->name }}</td>
            </tr>
            <tr class="border-b border-gray-300">
                <td class="p-2 font-bold">الموديل:</td>
                <td class="p-2">{{ $record->carModel->name }}</td>
            </tr>
            <tr class="border-b border-gray-300">
                <td class="p-2 font-bold">السنة:</td>
                <td class="p-2">{{ $record->vehicle_year }}</td>
            </tr>
            <tr class="border-b border-gray-300">
                <td class="p-2 font-bold">التاريخ:</td>
                <td class="p-2">{{ $record->requested_date->format('Y-m-d') }}</td>
            </tr>
            <tr class="border-b border-gray-300">
                <td class="p-2 font-bold">الوقت:</td>
                <td class="p-2">{{ $record->requested_date->isoFormat('h:mm a') }}</td>
            </tr>
            <tr>
                <td class="p-2 font-bold">الملاحظات:</td>
                <td class="p-2">{{ $record->notes }}</td>
            </tr>
        </tbody>
    </table>
</div>