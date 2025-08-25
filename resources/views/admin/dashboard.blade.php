@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>BI Dashboard</h1>
@stop

@section('content')
<div class="row">
    @foreach([
        ['label' => 'Clients', 'value' => $totalClients, 'icon' => 'users', 'bg' => 'info'],
        ['label' => 'Loan Applications', 'value' => $loanApplications, 'icon' => 'file-alt', 'bg' => 'primary'],
        ['label' => 'Approved Loans', 'value' => $approvedLoans, 'icon' => 'check-circle', 'bg' => 'success'],
        ['label' => 'Pending Approvals', 'value' => $pendingApprovals, 'icon' => 'hourglass-half', 'bg' => 'warning'],
        ['label' => 'Total Disbursed', 'value' => number_format($totalDisbursed), 'icon' => 'money-bill-wave', 'bg' => 'purple'],
        ['label' => 'Collections', 'value' => number_format($totalCollections), 'icon' => 'hand-holding-usd', 'bg' => 'maroon'],
    ] as $stat)
    <div class="col-md-4">
        <div class="small-box bg-{{ $stat['bg'] }}">
            <div class="inner">
                <h3>{{ $stat['value'] }}</h3>
                <p>{{ $stat['label'] }}</p>
            </div>
            <div class="icon">
                <i class="fas fa-{{ $stat['icon'] }}"></i>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card card-info">
            <div class="card-header"><h3 class="card-title">Monthly Loan Disbursements</h3></div>
            <div class="card-body">
                <canvas id="disbursementChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card card-success">
            <div class="card-header"><h3 class="card-title">Monthly Collections</h3></div>
            <div class="card-body">
                <canvas id="collectionChart"></canvas>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

    new Chart(document.getElementById('disbursementChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Disbursed (KES)',
                backgroundColor: '#17a2b8',
                data: @json(array_values($disbursements))
            }]
        }
    });

    new Chart(document.getElementById('collectionChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Collections (KES)',
                borderColor: '#28a745',
                data: @json(array_values($collections)),
                fill: false
            }]
        }
    });
</script>


@stop
