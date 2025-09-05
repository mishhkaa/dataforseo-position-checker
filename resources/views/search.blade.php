<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DataForSEO - Перевірка позицій сайту</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        .form-control {
            border-radius: 15px;
            border: 2px solid #e9ecef;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 15px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        .alert {
            border-radius: 15px;
            border: none;
        }
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }
        .header-icon {
            font-size: 3rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="text-center mb-5">
                    <i class="fas fa-search header-icon"></i>
                    <h1 class="text-white mt-3 mb-2">DataForSEO</h1>
                    <p class="text-white-50">Перевірка позицій сайту в Google</p>
                </div>
                
                <div class="card">
                    <div class="card-body p-5">
                        @if(session('success'))
                            <div class="alert alert-success d-flex align-items-center mb-4">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger d-flex align-items-center mb-4">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ session('error') }}
                            </div>
                        @endif
                        
                        <form action="{{ route('search.perform') }}" method="POST">
                            @csrf
                            

                            <div class="mb-4">
                                <label for="keyword" class="form-label">
                                    <i class="fas fa-key me-2"></i>Search Keyword
                                </label>
                                <input type="text" 
                                       class="form-control @error('keyword') is-invalid @enderror" 
                                       id="keyword" 
                                       name="keyword" 
                                       value="{{ old('keyword') }}"
                                       placeholder="Enter search keyword"
                                       required>
                                @error('keyword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="domain" class="form-label">
                                    <i class="fas fa-globe me-2"></i>Website Domain
                                </label>
                                <input type="text" 
                                       class="form-control @error('domain') is-invalid @enderror" 
                                       id="domain" 
                                       name="domain" 
                                       value="{{ old('domain') }}"
                                       placeholder="example.com"
                                       required>
                                @error('domain')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="location" class="form-label">
                                    <i class="fas fa-map-marker-alt me-2"></i>Location
                                </label>
                                <input type="text" 
                                       class="form-control @error('location') is-invalid @enderror" 
                                       id="location" 
                                       name="location" 
                                       value="{{ old('location', 'Ukraine') }}"
                                       placeholder="Ukraine"
                                       required>
                                @error('location')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-4">
                                <label for="language" class="form-label">
                                    <i class="fas fa-language me-2"></i>Language
                                </label>
                                <select class="form-control @error('language') is-invalid @enderror" 
                                        id="language" 
                                        name="language" 
                                        required>
                                    <option value="">Select Language</option>
                                    <option value="Ukrainian" {{ old('language') == 'Ukrainian' ? 'selected' : '' }}>Ukrainian</option>
                                    <option value="English" {{ old('language') == 'English' ? 'selected' : '' }}>English</option>
                                    <option value="Russian" {{ old('language') == 'Russian' ? 'selected' : '' }}>Russian</option>
                                </select>
                                @error('language')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-search me-2"></i>Search Position
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                

                <div class="text-center mt-4">
                    <p class="text-white-50 small">
                        <i class="fas fa-info-circle me-1"></i>
                        Powered by DataForSEO API v3
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>