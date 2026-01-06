@extends('layouts.app')

@section('page-title', 'Lost and Found')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/lost-found.css') }}">

@section('content')
<div class="lf-page">

    <h1>Report Lost & Found</h1>
    <p class="subtitle">
        Submit details about a lost or found item to help it get back to its owner.
    </p>

    <div class="lf-card">

        <div class="lf-group">
            <label>Item Status <span>*</span></label>
            <select>
                <option>Select status</option>
                <option>Lost</option>
                <option>Found</option>
            </select>
        </div>

        <div class="lf-group">
            <label>Item Name <span>*</span></label>
            <input type="text" placeholder="e.g. Black Backpack, iPhone">
        </div>

        <div class="lf-group">
            <label>Category</label>
            <select>
                <option>Electronics</option>
                <option>Wallet</option>
                <option>Bag</option>
                <option>Documents</option>
            </select>
        </div>

        <div class="lf-group">
            <label>Location <span>*</span></label>
            <input type="text" placeholder="Where the item was lost or found">
        </div>

        <div class="lf-row">
            <div class="lf-group">
                <label>Date <span>*</span></label>
                <input type="date">
            </div>

            <div class="lf-group">
                <label>Time</label>
                <input type="time">
            </div>
        </div>

        <div class="lf-group">
            <label>Description <span>*</span></label>
            <textarea placeholder="Provide details such as color, brand, unique marks"></textarea>
            <div class="lf-help">Minimum 20 characters</div>
        </div>

        <div class="lf-group">
            <label>Upload Image (Optional)</label>
            <div class="lf-upload">
                <div class="upload-icon">
                        ⬆️
                </div>
                <strong>Click to upload or drag and drop</strong>
                <span>PNG, JPG up to 10MB</span>
            </div>
        </div>

        <div class="lf-actions">
            <button class="lf-cancel">Cancel</button>
            <button class="lf-submit">Submit Report →</button>
        </div>

    </div>
</div>
@endsection
