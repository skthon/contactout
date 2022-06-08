@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/home">Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Referrals</li>
                        </ol>
                    </nav>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">Referred By</th>
                            <th scope="col">Referred email</th>
                            <th scope="col">Status</th>
                            <th scope="col">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($referrals as $referral)
                        <tr>
                            <td>{{ optional($referral->referrer)->name ?? "Unknown" }}</td>
                            <td>{{ $referral->referred_email  }}</td>
                            <td>{{ $referral->status ? "Invitation accepted" : "Invitation sent" }}</td>
                            <td>{{ $referral->created_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                    <div class="d-flex justify-content-center">
                        {!! $referrals->onEachSide(3)->links('pagination') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
