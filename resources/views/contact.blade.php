@extends('layout')
@section('titlepage','Website bán hàng online LaraSu24')
@section('title','Welcome')

@section('content')

<div class="container_cart">
    <div class="left-box-bill">
    <div class="contact-form">
        <form>
            <label for="name">Họ và Tên:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Nội dung:</label>
            <textarea id="message" name="message" rows="4" required></textarea>

            <button type="submit">Gửi</button>
        </form>
    </div>
</div>

<div class="right-box-bill">
        <h3>Thông tin liên hệ</h3>
        <p>
            <span>Địa chỉ:</span>
            <span>QTSC9, Khu CVPM Quang Trung, Q.12, TPHCM</span>
        </p>
        <p>
            <span>Email:</span>
            <span>hotb@fpt.edu.vn</span>
        </p>
        <p>
            <span>Số điện thoại:</span>
            <span>0123456789</span>
        </p>
        <p>
            <span>Website:</span>
            <span>caodang.fpt.edu.vn</span>
        </p>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.876175418411!2d106.67148267592472!3d10.744025489402796!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f53b037ee15%3A0x2a3ca847a178b438!2zQsOhIEjhu5kgWm9uZQ!5e0!3m2!1svi!2s!4v1709995499143!5m2!1svi!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>


    
</div>

@endsection