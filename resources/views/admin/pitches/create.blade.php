@extends('layouts.app')

@section('title', 'Create Pitch - Admin')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="display-6">Create New Pitch</h1>
                    <a href="{{ route('admin.pitches.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Pitches
                    </a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.pitches.store') }}">
                            @csrf

                            <div class="row g-3">
                                <!-- Basic Information -->
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3">Basic Information</h5>
                                </div>

                                <div class="col-md-6">
                                    <label for="stadium_id" class="form-label">Stadium *</label>
                                    <select class="form-select @error('stadium_id') is-invalid @enderror" 
                                            id="stadium_id" name="stadium_id" required>
                                        <option value="">Select Stadium</option>
                                        @foreach($stadiums as $stadium)
                                            <option value="{{ $stadium->id }}" {{ old('stadium_id') == $stadium->id ? 'selected' : '' }}>
                                                {{ $stadium->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('stadium_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="name" class="form-label">Pitch Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="type" class="form-label">Type *</label>
                                    <select class="form-select @error('type') is-invalid @enderror" 
                                            id="type" name="type" required>
                                        <option value="football" {{ old('type') == 'football' ? 'selected' : '' }}>Football</option>
                                        <option value="basketball" {{ old('type') == 'basketball' ? 'selected' : '' }}>Basketball</option>
                                        <option value="tennis" {{ old('type') == 'tennis' ? 'selected' : '' }}>Tennis</option>
                                        <option value="volleyball" {{ old('type') == 'volleyball' ? 'selected' : '' }}>Volleyball</option>
                                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="surface" class="form-label">Surface *</label>
                                    <select class="form-select @error('surface') is-invalid @enderror" 
                                            id="surface" name="surface" required>
                                        <option value="grass" {{ old('surface') == 'grass' ? 'selected' : '' }}>Grass</option>
                                        <option value="artificial" {{ old('surface') == 'artificial' ? 'selected' : '' }}>Artificial</option>
                                        <option value="clay" {{ old('surface') == 'clay' ? 'selected' : '' }}>Clay</option>
                                        <option value="concrete" {{ old('surface') == 'concrete' ? 'selected' : '' }}>Concrete</option>
                                    </select>
                                    @error('surface')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Pricing -->
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3 mt-4">Pricing & Slot Configuration</h5>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Slot Type *</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="slot_type" 
                                                       id="slot_60" value="60" {{ old('slot_type', '60') == '60' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="slot_60">
                                                    <strong>60 Minutes Only</strong>
                                                    <br><small class="text-muted">هتقسم على ساعة واحدة فقط</small>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="slot_type" 
                                                       id="slot_90" value="90" {{ old('slot_type') == '90' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="slot_90">
                                                    <strong>90 Minutes Only</strong>
                                                    <br><small class="text-muted">هتقسم على ساعة ونص فقط</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @error('slot_type')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                    <div class="alert alert-info mt-2">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Important:</strong> Choose ONE slot type only. The pitch will only generate slots of the selected duration.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="hourly_rate_60" class="form-label">60-Minute Rate (AED) *</label>
                                    <input type="number" step="0.01" class="form-control @error('hourly_rate_60') is-invalid @enderror" 
                                           id="hourly_rate_60" name="hourly_rate_60" value="{{ old('hourly_rate_60') }}" required>
                                    @error('hourly_rate_60')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Used only if 60-minute slots are selected</small>
                                </div>

                                <div class="col-md-6">
                                    <label for="hourly_rate_90" class="form-label">90-Minute Rate (AED) *</label>
                                    <input type="number" step="0.01" class="form-control @error('hourly_rate_90') is-invalid @enderror" 
                                           id="hourly_rate_90" name="hourly_rate_90" value="{{ old('hourly_rate_90') }}" required>
                                    @error('hourly_rate_90')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Used only if 90-minute slots are selected</small>
                                </div>

                                <!-- Operating Hours -->
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3 mt-4">Operating Hours</h5>
                                </div>

                                <div class="col-md-6">
                                    <label for="operating_start_time" class="form-label">Start Time *</label>
                                    <input type="time" class="form-control @error('operating_start_time') is-invalid @enderror" 
                                           id="operating_start_time" name="operating_start_time" value="{{ old('operating_start_time', '06:00') }}" required>
                                    @error('operating_start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="operating_end_time" class="form-label">End Time *</label>
                                    <input type="time" class="form-control @error('operating_end_time') is-invalid @enderror" 
                                           id="operating_end_time" name="operating_end_time" value="{{ old('operating_end_time', '23:00') }}" required>
                                    @error('operating_end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Operating Days *</label>
                                    <div class="row">
                                        @php
                                            $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                                            $oldDays = old('operating_days', $days);
                                        @endphp
                                        @foreach($days as $day)
                                            <div class="col-md-3 col-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           id="day_{{ $day }}" name="operating_days[]" 
                                                           value="{{ $day }}"
                                                           {{ in_array($day, $oldDays) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="day_{{ $day }}">
                                                        {{ ucfirst($day) }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @error('operating_days')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Additional Details -->
                                <div class="col-12">
                                    <h5 class="border-bottom pb-2 mb-3 mt-4">Additional Details</h5>
                                </div>

                                <div class="col-md-6">
                                    <label for="capacity" class="form-label">Capacity *</label>
                                    <input type="number" class="form-control @error('capacity') is-invalid @enderror" 
                                           id="capacity" name="capacity" value="{{ old('capacity', 22) }}" required>
                                    @error('capacity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="unavailable" {{ old('status') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Submit -->
                                <div class="col-12">
                                    <hr class="my-4">
                                    <div class="d-flex gap-3">
                                        <a href="{{ route('admin.pitches.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-2"></i>Create Pitch
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 