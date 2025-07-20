#!/bin/bash

# Stadium Booking API Test Script
# Make sure the server is running: php artisan serve

echo "🏟️ Stadium Booking API Test Script"
echo "==================================="
echo

BASE_URL="http://localhost:8000/api"

# Colors for output
GREEN='\033[0;32m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

echo -e "${BLUE}1. Testing: Get All Stadiums${NC}"
curl -s "$BASE_URL/stadiums" | head -c 200
echo -e "\n\n"

echo -e "${BLUE}2. Testing: Get Available Slots for Stadium 1${NC}"
TOMORROW=$(date -v +1d +%Y-%m-%d 2>/dev/null || date -d '+1 day' +%Y-%m-%d 2>/dev/null)
curl -s "$BASE_URL/stadiums/1/available-slots?date=$TOMORROW" | head -c 300
echo -e "\n\n"

echo -e "${BLUE}3. Testing: Create a Booking${NC}"
BOOKING_RESPONSE=$(curl -s -X POST "$BASE_URL/bookings" \
  -H "Content-Type: application/json" \
  -d "{
    \"pitch_id\": 1,
    \"user_name\": \"Test User\",
    \"user_email\": \"test@example.com\",
    \"user_phone\": \"+971501234567\",
    \"booking_date\": \"$TOMORROW\",
    \"start_time\": \"14:00\",
    \"end_time\": \"15:00\",
    \"duration_minutes\": 60,
    \"notes\": \"API test booking\"
  }")

echo "$BOOKING_RESPONSE" | head -c 300
echo -e "\n\n"

# Extract booking ID if successful
BOOKING_ID=$(echo "$BOOKING_RESPONSE" | grep -o '"id":[0-9]*' | cut -d':' -f2)

if [ ! -z "$BOOKING_ID" ]; then
    echo -e "${GREEN}✅ Booking created successfully with ID: $BOOKING_ID${NC}"
    echo
    
    echo -e "${BLUE}4. Testing: Get User Bookings${NC}"
    curl -s "$BASE_URL/bookings/user/bookings?email=test@example.com" | head -c 300
    echo -e "\n\n"
    
    echo -e "${BLUE}5. Testing: Get Available Slots (should exclude the booked slot)${NC}"
    curl -s "$BASE_URL/stadiums/1/available-slots?date=$TOMORROW" | head -c 400
    echo -e "\n\n"
    
    echo -e "${BLUE}6. Testing: Try to Book the Same Slot (should fail)${NC}"
    DUPLICATE_RESPONSE=$(curl -s -X POST "$BASE_URL/bookings" \
      -H "Content-Type: application/json" \
      -d "{
        \"pitch_id\": 1,
        \"user_name\": \"Another User\",
        \"user_email\": \"another@example.com\",
        \"user_phone\": \"+971501234567\",
        \"booking_date\": \"$TOMORROW\",
        \"start_time\": \"14:00\",
        \"end_time\": \"15:00\",
        \"duration_minutes\": 60
      }")
    
    echo "$DUPLICATE_RESPONSE" | head -c 200
    echo -e "\n\n"
    
    if echo "$DUPLICATE_RESPONSE" | grep -q "already booked"; then
        echo -e "${GREEN}✅ Overbooking prevention working correctly${NC}"
    else
        echo -e "${RED}❌ Overbooking prevention failed${NC}"
    fi
else
    echo -e "${RED}❌ Booking creation failed${NC}"
fi

echo
echo -e "${BLUE}7. Testing: Invalid Stadium ID (should return 404)${NC}"
curl -s "$BASE_URL/stadiums/999/available-slots?date=$TOMORROW" | head -c 200
echo -e "\n\n"

echo -e "${BLUE}8. Testing: Invalid Date Format (should return 422)${NC}"
curl -s "$BASE_URL/stadiums/1/available-slots?date=invalid-date" | head -c 200
echo -e "\n\n"

echo "🎯 API Test Complete!"
echo "For more detailed testing, run: php artisan test" 