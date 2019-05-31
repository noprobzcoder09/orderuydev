<?php

// @section('breadcrumbs', Breadcrumbs::render('new-customer'))

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('dashboard'));
});

// Home > Customers
Breadcrumbs::for('customers', function ($trail) {
    $trail->parent('home');
    $trail->push('Customers', route('customers'));
});

// Home > Audit Logs
Breadcrumbs::for('customer-audit', function ($trail) {
    $trail->parent('customers');
    $trail->push('Customer Activity Logs', route('customer.audit.logs.index', 'id'));
});

// Home > Audit Logs > View
Breadcrumbs::for('customer-audit-view', function ($trail) {
    $trail->parent('customer-audit');
    $trail->push('View Customer Activity Log', route('customer.audit.logs.show', ['id', 'customer_id']));
});

// Home > Customers > Find Email
Breadcrumbs::for('find-email', function ($trail) {
    $trail->parent('customers');
    $trail->push('Find Email', route('customers.find-email'));
});

// Home > Customers > Find Email > New
Breadcrumbs::for('new-customer', function ($trail) {
    $trail->parent('find-email');
    $trail->push('New', route('customers.find-email.new'));
});



// Home > Customers > Edit
Breadcrumbs::for('edit-customer', function ($trail, $id) {
    $trail->parent('customers');
    $trail->push('Edit', route('customers.edit', ['id' => $id]));
});

// Home > Customers > Invoice
Breadcrumbs::for('customer-invoice', function ($trail, $id) {
    $trail->parent('customers');
    $trail->push('Invoice', route('customers.invoice.show', ['id' => $id]));
});

// Home > Customers > Billig Issue
Breadcrumbs::for('customer-billing-issue', function ($trail) {
    $trail->parent('customers');
    $trail->push('Billing Issue', route('customers.billing-issue'));
});


// Home > Products
Breadcrumbs::for('products', function ($trail) {
    $trail->parent('home');
    $trail->push('Products', route('products.plan.all'));
});

// Home > Products > Plans
Breadcrumbs::for('plans-for-products', function ($trail) {
    $trail->parent('products');
    $trail->push('Plans', route('products.plan.all'));
});

// Home > Products > Plans > All
Breadcrumbs::for('product-plans', function ($trail) {
    $trail->parent('plans-for-products');
    $trail->push('All Plans', route('products.plan.all'));
});

// Home > Products > Plans > New
Breadcrumbs::for('product-plans-new', function ($trail) {
    $trail->parent('plans-for-products');
    $trail->push('New', route('products.plan.new'));
});

// Home > Products > Plans > Edit
Breadcrumbs::for('product-plans-edit', function ($trail, $id) {
    $trail->parent('plans-for-products');
    $trail->push('Edit', route('products.plan.edit', ['id' => $id]));
});

// Home > Products > Plans > Scheduler
Breadcrumbs::for('product-plans-scheduler', function ($trail) {
    $trail->parent('plans-for-products');
    $trail->push('Scheduler', route('products.plan.scheduler'));
});


// Home > Products
Breadcrumbs::for('products-meal', function ($trail) {
    $trail->parent('home');
    $trail->push('Products', route('products.meal.all'));
});

// Home > Products > Meals
Breadcrumbs::for('meals-for-products', function ($trail) {
    $trail->parent('products-meal');
    $trail->push('Meals', route('products.meal.all'));
});

// Home > Products > Meals > All
Breadcrumbs::for('product-meals', function ($trail) {
    $trail->parent('meals-for-products');
    $trail->push('All Meals', route('products.meal.all'));
});

// Home > Products > Meals > New
Breadcrumbs::for('product-meals-new', function ($trail) {
    $trail->parent('meals-for-products');
    $trail->push('New', route('products.meal.new'));
});

// Home > Products > Meals > Edit
Breadcrumbs::for('product-meals-edit', function ($trail, $id) {
    $trail->parent('meals-for-products');
    $trail->push('Edit', route('products.meal.edit', ['id' => $id]));
});


// Home > Delivery
Breadcrumbs::for('delivery', function ($trail) {
    $trail->parent('home');
    $trail->push('Delivery', route('delivery.zone.all'));
});

// Home > Delivery  > Zone
Breadcrumbs::for('delivery-zone', function ($trail) {
    $trail->parent('delivery');
    $trail->push('Zone', route('delivery.zone.all'));
});

// Home > Delivery > Zone > All Zones
Breadcrumbs::for('delivery-zone-all', function ($trail) {
    $trail->parent('delivery-zone');
    $trail->push('All Zones', route('delivery.zone.all'));
});

// Home > Delivery > Zone > New
Breadcrumbs::for('delivery-zone-new', function ($trail) {
    $trail->parent('delivery-zone');
    $trail->push('New', route('delivery.zone.new'));
});

// Home > Delivery > Zone > Edit
Breadcrumbs::for('delivery-zone-edit', function ($trail, $id) {
    $trail->parent('delivery-zone');
    $trail->push('Edit', route('delivery.zone.edit', ['id' => $id]));
});




// Home > Delivery
Breadcrumbs::for('delivery-timing-root', function ($trail) {
    $trail->parent('home');
    $trail->push('Delivery', route('delivery.timing.all'));
});

// Home > Delivery  > Timing
Breadcrumbs::for('delivery-timing', function ($trail) {
    $trail->parent('delivery-timing-root');
    $trail->push('Timing', route('delivery.timing.all'));
});

// Home > Delivery > Timing > All Timings
Breadcrumbs::for('delivery-timing-all', function ($trail) {
    $trail->parent('delivery-timing');
    $trail->push('All Timings', route('delivery.timing.all'));
});

// Home > Delivery > Timing > New
Breadcrumbs::for('delivery-timing-new', function ($trail) {
    $trail->parent('delivery-timing');
    $trail->push('New', route('delivery.timing.new'));
});

// Home > Delivery > Timing > Edit
Breadcrumbs::for('delivery-timing-edit', function ($trail, $id) {
    $trail->parent('delivery-timing');
    $trail->push('Edit', route('delivery.timing.edit', ['id' => $id]));
});





// Home > Delivery
Breadcrumbs::for('delivery-zone-timing-root', function ($trail) {
    $trail->parent('home');
    $trail->push('Delivery', route('delivery.zone.timing.all'));
});

// Home > Delivery  > Zone
Breadcrumbs::for('delivery-zone-timing', function ($trail) {
    $trail->parent('delivery-zone-timing-root');
    $trail->push('Zone', route('delivery.zone.timing.all'));
});

// Home > Delivery  > Zone > Timing
Breadcrumbs::for('delivery-zone-timing-second', function ($trail) {
    $trail->parent('delivery-zone-timing');
    $trail->push('Timing', route('delivery.zone.timing.all'));
});

// Home > Delivery > Zone > Timing > All
Breadcrumbs::for('delivery-zone-timing-all', function ($trail) {
    $trail->parent('delivery-zone-timing-second');
    $trail->push('All Zone Timings', route('delivery.zone.timing.all'));
});

// Home > Delivery > Zone > Timing > New
Breadcrumbs::for('delivery-zone-timing-new', function ($trail) {
    $trail->parent('delivery-zone-timing-second');
    $trail->push('New', route('delivery.zone.timing.new'));
});

// Home > Delivery > Zone > Timing > Edit
Breadcrumbs::for('delivery-zone-timing-edit', function ($trail, $id) {
    $trail->parent('delivery-zone-timing-second');
    $trail->push('Edit', route('delivery.zone.timing.edit', ['id' => $id]));
});




// Home > Coupons
Breadcrumbs::for('coupons', function ($trail) {
    $trail->parent('home');
    $trail->push('Coupons', route('delivery.zone.timing.all'));
});

// Home > Coupons  > All Coupons
Breadcrumbs::for('coupons-all', function ($trail) {
    $trail->parent('coupons');
    $trail->push('All Coupons', route('coupons.all'));
});

// Home > Coupons  > Edit
Breadcrumbs::for('coupons-edit', function ($trail, $id) {
    $trail->parent('coupons-all');
    $trail->push('Edit', route('coupons.edit', ['id' => $id]));
});

// Home > Coupons  > New
Breadcrumbs::for('coupons-new', function ($trail) {
    $trail->parent('coupons-all');
    $trail->push('New', route('coupons.new'));
});



// Home > Users
Breadcrumbs::for('users', function ($trail) {
    $trail->parent('home');
    $trail->push('Users', route('users.all'));
});

// Home > Users  > All Users
Breadcrumbs::for('users-all', function ($trail) {
    $trail->parent('users');
    $trail->push('All Users', route('users.all'));
});

// Home > Users  > Edit
Breadcrumbs::for('users-edit', function ($trail, $id) {
    $trail->parent('users');
    $trail->push('Edit', route('users.edit', ['id' => $id]));
});

// Home > Users  > New
Breadcrumbs::for('users-new', function ($trail) {
    $trail->parent('users');
    $trail->push('New', route('users.new'));
});


// Home > Users
Breadcrumbs::for('reports', function ($trail) {
    $trail->parent('home');
    $trail->push('Reports', route('reports.all'));
});


// Home > Audit Logs
Breadcrumbs::for('audit', function ($trail) {
    $trail->parent('home');
    $trail->push('Activity Logs', route('audit.logs.index'));
});

// Home > Audit Logs > View
Breadcrumbs::for('audit-view', function ($trail) {
    $trail->parent('audit');
    $trail->push('View Activity Log', route('audit.logs.show', 'id'));
});


// Home > Audit Logs
Breadcrumbs::for('api', function ($trail) {
    $trail->parent('home');
    $trail->push('Api Settings', route('settings.api.index'));
});