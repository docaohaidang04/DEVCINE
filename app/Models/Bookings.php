<?php

namespace App\Models;

use App\Mail\BookingConfirmationMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class Bookings extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $primaryKey = 'id_booking';

    protected $fillable = [
        'account_id',
        'account_promotion_id',
        'id_payment',
        'id_ticket',
        'booking_code',
        'booking_date',
        'total_amount',
        'payment_status',
        'transaction_id',
        'payment_date',
        'status',
    ];

    // Mối quan hệ một-một với Account
    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id', 'id_account');
    }

    // Mối quan hệ nhiều-nhiều với Products
    public function products()
    {
        return $this->belongsToMany(Product::class, 'booking_product', 'id_booking', 'id_product')
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function ticket()
    {
        return $this->belongsTo(Tickets::class, 'id_ticket', 'id_ticket');
    }
    // Lấy tất cả bookings
    public static function getAllBookings()
    {
        // Kiểm tra nếu account_id hợp lệ


        // Lấy danh sách booking theo account_id và kèm theo các quan hệ liên quan
        $bookings = self::with([
            'account',
            'products',
            'ticket',
            'ticket.chairs',
            'ticket.showtime',
            'ticket.showtime.movie'
        ])->get();

        // Kiểm tra nếu không tìm thấy booking nào
        if ($bookings->isEmpty()) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Định dạng dữ liệu
        $formattedBookings = $bookings->map(function ($booking) {
            return [
                'id_booking' => $booking->id_booking,
                'booking_code' => $booking->booking_code,
                'booking_date' => $booking->booking_date,
                'total_amount' => $booking->total_amount,
                'payment_status' => $booking->payment_status,
                'status' => $booking->status,
                'account' => [
                    'id' => $booking->account->id_account,
                    'user_name' => $booking->account->user_name,
                    'email' => $booking->account->email,
                    'full_name' => $booking->account->full_name,
                    'avatar' => $booking->account->avatar,
                ],
                'products' => $booking->products->map(function ($product) {
                    return [
                        'id_product' => $product->id_product,
                        'product_name' => $product->product_name,
                        'price' => $product->price,
                        'quantity' => $product->pivot->quantity,
                        'image_product' => $product->image_product,
                    ];
                }),
                'chairs' => $booking->ticket->chairs->map(function ($chair) {
                    return [
                        'id_chair' => $chair->id_chair,
                        'chair_name' => $chair->chair_name,
                        'price' => $chair->price,
                    ];
                }),
                'showtime' => [
                    'date_time' => $booking->ticket->showtime->date_time,
                    'start_time' => $booking->ticket->showtime->start_time,
                    'end_time' => $booking->ticket->showtime->end_time,

                ],
                'movie_name' => $booking->ticket->showtime->movie->movie_name

            ];
        });

        // Trả về dữ liệu đã định dạng
        return $formattedBookings;
    }

    // Lấy booking theo id
    public static function getBookingById($id_booking)
    {
        // Tìm booking theo id_booking và kèm theo các quan hệ liên quan
        $booking = self::with([
            'account',
            'products',
            'ticket',
            'ticket.chairs',
            'ticket.showtime',
            'ticket.showtime.movie'
        ])->where('id_booking', $id_booking)->first();

        // Kiểm tra nếu không tìm thấy booking
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Định dạng dữ liệu
        $formattedBooking = [
            'id_booking' => $booking->id_booking,
            'booking_code' => $booking->booking_code,
            'booking_date' => $booking->booking_date,
            'total_amount' => $booking->total_amount,
            'payment_status' => $booking->payment_status,
            'status' => $booking->status,
            'account' => [
                'id' => $booking->account->id_account,
                'user_name' => $booking->account->user_name,
                'email' => $booking->account->email,
                'full_name' => $booking->account->full_name,
                'avatar' => $booking->account->avatar,
            ],
            'products' => $booking->products->map(function ($product) {
                return [
                    'id_product' => $product->id_product,
                    'product_name' => $product->product_name,
                    'price' => $product->price,
                    'quantity' => $product->pivot->quantity,
                    'image_product' => $product->image_product,
                ];
            }),
            'chairs' => $booking->ticket->chairs->map(function ($chair) {
                return [
                    'id_chair' => $chair->id_chair,
                    'chair_name' => $chair->chair_name,
                    'price' => $chair->price,
                ];
            }),
            'showtime' => [
                'date_time' => $booking->ticket->showtime->date_time,
                'start_time' => $booking->ticket->showtime->start_time,
                'end_time' => $booking->ticket->showtime->end_time,
            ],
            'movie_name' => $booking->ticket->showtime->movie->movie_name
        ];

        // Trả về dữ liệu đã định dạng
        return  response()->json($formattedBooking, 200);
    }


    // Tạo booking mới
    public static function createBooking($data)
    {
        $validator = Validator::make($data, [
            'account_id' => 'required|exists:accounts,id_account',
            'account_promotion_id' => 'nullable|exists:account_promotion,account_promotion_id',
            'id_products' => 'nullable|array',
            'id_products.*.id_product' => 'required|exists:products,id_product',
            'id_products.*.quantity' => 'required|integer|min:1',
            'id_payment' => 'nullable|exists:payment,id_payment',
            'id_ticket' => 'nullable|exists:tickets,id_ticket',
            'booking_code' => 'nullable|string|max:255',
            'total_amount' => 'nullable|numeric|min:0',
            'payment_status' => 'nullable|string',
            'transaction_id' => 'nullable|string|max:255',
            'booking_date' => 'nullable|date',
            'status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Lưu booking
        $booking = self::create([
            'account_id' => $data['account_id'],
            'account_promotion_id' => $data['account_promotion_id'] ?? null,
            'id_payment' => $data['id_payment'] ?? null,
            'id_ticket' => $data['id_ticket'] ?? null,
            'booking_code' => $data['booking_code'] ?? null,
            'total_amount' => $data['total_amount'] ?? 0,
            'payment_status' => $data['payment_status'] ?? null,
            'transaction_id' => $data['transaction_id'] ?? null,
            'booking_date' => $data['booking_date'] ?? null,
            'status' => $data['status'] ?? 'pending',
        ]);

        // Thêm sản phẩm vào booking nếu có
        if (isset($data['id_products']) && is_array($data['id_products'])) {
            $productsWithQuantity = [];

            foreach ($data['id_products'] as $product) {
                // Kiểm tra và ánh xạ id_product với quantity
                if (isset($product['id_product'], $product['quantity'])) {
                    $productsWithQuantity[$product['id_product']] = [
                        'quantity' => $product['quantity'],
                    ];
                }
            }

            // Lưu dữ liệu vào bảng trung gian
            $booking->products()->sync($productsWithQuantity);
        }

        return $booking;
    }


    // Cập nhật booking
    public function updateBooking($data)
    {
        $validator = Validator::make($data, [
            'account_id' => 'nullable|exists:accounts,id_account',
            'account_promotion_id' => 'nullable|exists:account_promotions,id_account_promotion',
            'id_products' => 'nullable|array',
            'id_products.*.id_product' => 'required|exists:products,id_product',
            'id_products.*.quantity' => 'required|integer|min:1',
            'id_payment' => 'nullable|exists:payments,id_payment',
            'id_ticket' => 'nullable|exists:tickets,id_ticket',
            'booking_code' => 'nullable|string|max:255',
            'total_amount' => 'nullable|numeric|min:0',
            'payment_status' => 'nullable|string',
            'transaction_id' => 'nullable|string|max:255',
            'payment_date' => 'nullable|date',
            'status' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Cập nhật thông tin booking
        $this->update($data);

        // Cập nhật lại sản phẩm và số lượng
        if (isset($data['id_products']) && is_array($data['id_products'])) {
            $productsWithQuantity = [];
            foreach ($data['id_products'] as $product) {
                $productsWithQuantity[$product['id_product']] = ['quantity' => $product['quantity']];
            }
            $this->products()->sync($productsWithQuantity);
        }

        return $this;
    }


    // Xóa booking
    public static function deleteBooking($id)
    {
        $booking = self::find($id);
        if ($booking) {
            $booking->delete();
            return response()->json(['message' => 'Booking deleted successfully'], 200);
        }

        return response()->json(['message' => 'Booking not found'], 404);
    }

    public static function getBookingsByAccountId($account_id)
    {
        // Kiểm tra nếu account_id hợp lệ
        if (empty($account_id)) {
            return response()->json(['message' => 'Account ID is required'], 400);
        }

        // Lấy danh sách booking theo account_id và kèm theo các quan hệ liên quan
        $bookings = self::with([
            'account',
            'products',
            'ticket',
            'ticket.chairs',
            'ticket.showtime',
            'ticket.showtime.movie'
        ])->where('account_id', $account_id)->get();

        // Kiểm tra nếu không tìm thấy booking nào
        if ($bookings->isEmpty()) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        // Định dạng dữ liệu
        $formattedBookings = $bookings->map(function ($booking) {
            return [
                'id_booking' => $booking->id_booking,
                'booking_code' => $booking->booking_code,
                'booking_date' => $booking->booking_date,
                'total_amount' => $booking->total_amount,
                'payment_status' => $booking->payment_status,
                'status' => $booking->status,
                'account' => [
                    'id' => $booking->account->id_account,
                    'user_name' => $booking->account->user_name,
                    'email' => $booking->account->email,
                    'full_name' => $booking->account->full_name,
                    'avatar' => $booking->account->avatar,
                ],
                'products' => $booking->products->map(function ($product) {
                    return [
                        'id_product' => $product->id_product,
                        'product_name' => $product->product_name,
                        'price' => $product->price,
                        'quantity' => $product->pivot->quantity,
                        'image_product' => $product->image_product,
                    ];
                }),
                'chairs' => $booking->ticket->chairs->map(function ($chair) {
                    return [
                        'id_chair' => $chair->id_chair,
                        'chair_name' => $chair->chair_name,
                        'price' => $chair->price,
                    ];
                }),
                'showtime' => [
                    'date_time' => $booking->ticket->showtime->date_time,
                    'start_time' => $booking->ticket->showtime->start_time,
                    'end_time' => $booking->ticket->showtime->end_time,

                ],
                'movie_name' => $booking->ticket->showtime->movie->movie_name

            ];
        });

        // Trả về dữ liệu đã định dạng
        return response()->json($formattedBookings, 200);
    }

    protected static function booted()
    {
        static::updated(function ($booking) {
            if ($booking->isDirty('payment_status') && $booking->payment_status == 'success') {
                // Gửi email xác nhận
                Mail::to($booking->account->email)->send(new BookingConfirmationMail($booking));

                $account = $booking->account;
                if ($account) {
                    // Tính điểm loyalty theo số tiền thanh toán
                    $loyaltyPoints = round($booking->total_amount / 10000);
                    $account->increment('loyalty_points', $loyaltyPoints);
                }
            }
        });
    }
}
