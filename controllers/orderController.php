<?php

require_once 'models/orderModel.php';
class OrderController
{
    public function createOrder($data, $userId)
    {
        $orderModel = new OrderModel();
        $orderDetail = $orderModel->createOrder($data, $userId);
        if ($orderDetail['statusCode'] == '200') {
            // send email
            $user = $_SESSION['user'];
            $email = $user['email'];
            $name = $user['name'];
            $subject = "Order Confirmation";
            $body = "Hi $name, <br><br> Your order has been placed successfully. <br><br> Thank you for shopping with us. <br><br> Regards, <br> Team E-Commerce";

            // create invoice using file handling
            $order_id = $orderDetail['orderId'];
            $order = pg_query($GLOBALS['db'], "SELECT o.* , od.* , p.title, p.price 
                                            FROM orders o, orders_detail od, products p 
                                            WHERE o.id = od.order_id 
                                            AND od.product_id = p.id 
                                            AND o.id = '$order_id'");
            $order = pg_fetch_all($order);

            $invoice = fopen("invoices/invoice_$order_id.html", "w") or die("Unable to open file!");

            $invoiceContent = "<html>
            <head>
            <style>
            table, th, td {
                border: 1px solid black;
                                        border-collapse: collapse;
                                    }
                                    th, td {
                                        padding: 5px;
                                        text-align: left;
                                    }
                                </style>
                            </head>
                            <body>
                            <h2>Order Details</h2>
                            <table style='width:100%'>
                            <tr>
                            <th>Order ID</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            </tr>";
            foreach ($order as $item) {
                $invoiceContent .= "<tr>
                                    <td>$item[order_id]</td>
                                    <td>$item[title]</td>
                                    <td>$item[quantity]</td>
                                    <td>$item[price]</td>
                                </tr>";
            }
            $invoiceContent .= "</table>
                            </body>
                            </html>";

            fwrite($invoice, $invoiceContent);
            fclose($invoice);

            // smtp_mailer($email, $subject, $body, "invoices/invoice_$order_id.html", "invoice_$order_id.html");
            smtp_mailer($email, $subject, $body, null, null);
        }
        return $orderDetail;

    }
}