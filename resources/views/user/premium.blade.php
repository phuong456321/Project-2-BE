<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Subscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #061223;
            color: #fff;
            background-image: url('/images/profile/background.png');
        }

        .pricing-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .pricing-header img {
            width: 162px;
            height: 108px;
            position: absolute;
            top: 6px;
            left: 13px;
        }

        .pricing-header h1 {
            font-size: 2.5rem;
            font-weight: bold;
            padding-bottom: 3rem;
        }

        .card {
            background-color: #444;
            border: none;
            color: #fff;
        }

        .card h3,
        .card h2 {
            font-weight: bold;
        }

        .recommended {
            color: #007bff;
            font-size: 0.9rem;
        }

        .card-body {
            text-align: center;
        }

        .btn-buy {
            background-color: #007bff;
            color: #fff;
            border: none;
        }

        .btn-buy:hover {
            background-color: #0056b3;
        }

        .card-price {
            font-size: 2rem;
        }

        .discount-price {
            text-decoration: line-through;
            color: #999;
        }
        .custom-pt{
            padding-bottom: 14rem! important;
            padding-top: 9rem!important;
        }
    </style>
</head>

<body>
    <div class="custom-pt">
        <div class="pricing-header mb-7">
            <a href="{{ route('home') }}" id="home">
                <img src="{{ asset('images/profile/logo-home.png') }}" alt="Logo" width="200">
            </a>
            <h1>Go Premium for Ad-Free Music and More!</h1>
            <p>Cancel anytime - you're in control! By signing up for Premium, you agree to our <a href="#" class="text-decoration-underline">Terms of Service</a> and <a href="#" class="text-decoration-underline">Privacy Policy</a>.</p>
        </div>

        <div class="row justify-content-center">
            <!-- Free Plan -->
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-body">
                        <h3>Free</h3>
                        <h2 class="card-price">Free</h2>
                        <p>Free plan includes music with ads.</p>
                        <button class="btn btn-secondary mt-3" disabled>Current</button>
                    </div>
                </div>
            </div>
            <!-- Monthly Plan -->
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-body">
                        <h3>Month</h3>
                        <h2 class="card-price">1.59$</h2>
                        <p>Ad-free music experience.</p>
                        <p>Ability to upload your own tracks.</p>
                        <button class="btn btn-outline-light mt-3">Buy</button>
                    </div>
                </div>
            </div>
            <!-- Yearly Plan -->
            <div class="col-md-3">
                <div class="card mb-4 border-primary">
                    <div class="card-body">
                        <h3>Year</h3>
                        <h2 class="card-price text-primary">15$ <span class="discount-price">19.98$</span></h2>
                        <span class="recommended">Recommended</span>
                        <p>Ad-free music experience.</p>
                        <p>Ability to upload your own tracks.</p>
                        <button class="btn btn-buy mt-3">Buy</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>