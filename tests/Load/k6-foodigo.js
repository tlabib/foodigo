import http from 'k6/http';
import { check, sleep } from 'k6';

const BASE_URL = __ENV.BASE_URL || 'http://foodigo.test';
const RESTAURANT_ID = __ENV.RESTAURANT_ID || '21';
const ORDER_ID = __ENV.ORDER_ID || '';
const AUTH_COOKIE = __ENV.AUTH_COOKIE || '';

export const options = {
  vus: 10,
  duration: '30s',
  thresholds: {
    http_req_failed: ['rate<0.05'],
    http_req_duration: ['p(95)<800'],
  },
};

function getHeaders() {
  if (!AUTH_COOKIE) {
    return {};
  }

  return {
    headers: {
      Cookie: AUTH_COOKIE,
    },
  };
}

export default function () {
  const homeRes = http.get(`${BASE_URL}/`);
  check(homeRes, {
    'home status is 200': (r) => r.status === 200,
  });

  const restaurantRes = http.get(`${BASE_URL}/restaurants/${RESTAURANT_ID}`);
  check(restaurantRes, {
    'restaurant page status is 200': (r) => r.status === 200,
  });

  const restaurantsApiRes = http.get(`${BASE_URL}/api/restaurants`);
  check(restaurantsApiRes, {
    'restaurants api status is 200': (r) => r.status === 200,
  });

  if (ORDER_ID && AUTH_COOKIE) {
    const orderApiRes = http.get(`${BASE_URL}/api/orders/${ORDER_ID}`, getHeaders());
    check(orderApiRes, {
      'order api status is 200': (r) => r.status === 200,
    });
  }

  sleep(1);
}
