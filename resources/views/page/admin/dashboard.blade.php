@extends('layouts.admin.app')

@section('title', 'Dashboard')
@section('header-title', 'Dashboard')

@section('content')
    <div class="cards">
      <div class="card sales">
        <div class="icon">💰</div>
        <div class="info">
          <div class="title">Total Sales</div>
          <div class="value" id="totalSales">{{ number_format($totalPrice, 0, ',', '.') }}</div>
        </div>
      </div>
      <div class="card orders">
        <div class="icon">🛒</div>
        <div class="info">
          <div class="title">Orders</div>
          <div class="value" id="totalOrders">{{ number_format($totalOrders->count(), 0, ',', '.') }}</div>
        </div>
      </div>
      <div class="card customers">
        <div class="icon">👥</div>
        <div class="info">
          <div class="title">Customers</div>
          <div class="value" id="totalCustomers">{{ number_format($totalCustomers, 0, ',', '.') }}</div>
        </div>
      </div>
      <div class="card inventory">
        <div class="icon">📦</div>
        <div class="info">
          <div class="title">Inventory</div>
          <div class="value" id="inventoryCount">{{ number_format($totalProducts->count(), 0, ',', '.') }}</div>
        </div>
      </div>
    </div>

    <section>
      <h2 class="section-title">Recent Orders</h2>
      <table aria-label="Recent orders">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Date</th>
            <th>Status</th>
            <th>Shipping address</th>
            <th>Total</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="ordersTableBody">
          @foreach($getListOrder5 as $order)
            <tr>
                <td>#{{ $order->id }}</td>
                <td>{{ $order->user->name }}</td>
                <td>{{ $order->user->phone }}</td>
                <td>{{ $order->user->email }}</td>
                <td>{{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}</td>
                <td>
                    @php
                        $status = $order->payment_status;
                        $color = match($status) {
                            'Completed' => '#10b981',
                            'Cancelled' => '#ff4c60',
                            'Pending' => '#f59e0b',
                            default => '#6b7280'
                        };
                    @endphp
                    <span style="color:{{ $color }}; font-weight:600;">{{ $status }}</span>
                </td>
                <td>{{ $order->shipping_address }}</td>
                <td>{{ number_format($order->total_price, 0, ',', '.') }}</td>
                <td>
                    {{-- <button class="btn edit">Edit</button>  --}}
                    <button class="btn delete">Delete</button>
                </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </section>

    <section style="margin-top: 50px;">
      <h2 class="section-title">Top 5 Best Sellers</h2>
      <table aria-label="Top 5 Best Sellers">
        <thead>
          <tr>
            <th></th>
            <th>Product ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Quantity sold</th>
          </tr>
        </thead>
        <tbody id="ordersTableBody">
          @foreach($top5BestSellers as $index => $product)
            <tr>
              <td>
                @php
                  $colors = ['red', 'green', 'blue'];
                @endphp
                <h4 style="{{ $index < 3 ? 'color: ' . $colors[$index] : '' }}">
                  Top {{ $index + 1 }}
                </h4>
              </td>
              <td>{{ $product->id }}</td>
              <td><img src="{{ asset('storage/' . $product->images->first()->image_url) }}" alt="Ảnh sản phẩm" style="max-width: 100%; max-height: 70px; object-fit: contain;"></td>
              <td>{{ $product->name }}</td>
              <td>{{ number_format($product->quantity_sold, 0, ',', '.') }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </section>

    <section id="chart-container" style="margin-top: 50px;">
      <h2 class="section-title">Sales Overview</h2>
      <canvas id="salesChart" height="100"></canvas>
    </section>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');

    // Truyền dữ liệu từ PHP sang JS
    const labels = @json($labels);
    const salesData = @json($data);

    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Sales (VND)',
                data: salesData,
                fill: true,
                backgroundColor: 'rgba(75, 108, 183, 0.2)',
                borderColor: '#4b6cb7',
                borderWidth: 3,
                tension: 0.3,
                pointRadius: 6,
                pointBackgroundColor: '#4b6cb7',
                pointHoverRadius: 8,
                pointHoverBackgroundColor: '#335090'
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 200000 }
                }
            },
            plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 14,
                            weight: '800'
                        }
                    }
                }
            }
        }
    });
</script>
@endpush