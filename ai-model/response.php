<?php
require_once '../vendor/autoload.php';

use Phpml\ModelManager;

// Load the trained model
$modelManager = new ModelManager();
$classifier = $modelManager->restoreFromFile('chatbot_model.model');

// Get the keyword from AJAX request
if (isset($_POST['keyword'])) {
  $keyword = $_POST['keyword'];

  // Preprocess the keyword (convert to lowercase and split into words)
  function preprocess($text)
  {
    return explode(' ', strtolower($text));  // First convert to lowercase, then split into words
  }

  $processedKeyword = preprocess($keyword);  // Preprocess the input keyword

  // Predict the category based on the trained model
  try {
    $category = $classifier->predict($processedKeyword);

    // Generate response message based on the predicted category
    switch ($category) {
      case 'greeting':
        $responseMessage = "Hi there! How can I help you today?";
        break;
      case 'farewell':
        $responseMessage = "Goodbye! Have a great day!";
        break;
      case 'help':
        $responseMessage = "I'm here to assist you! Please let me know what you need help with.";
        break;
      default:
        $responseMessage = "I'm not sure how to respond to that.";
    }

    // Prepare the success JSON response
    $response = [
      'status' => 'success',
      'message' => $responseMessage
    ];
  } catch (Exception $e) {
    // Handle any prediction errors
    $response = [
      'status' => 'error',
      'message' => 'An error occurred while processing your request.'
    ];
  }
} else {
  // Return an error response if no keyword is provided
  $response = [
    'status' => 'error',
    'message' => 'No keyword provided.'
  ];
}

// Set content type to JSON and return the response
header('Content-Type: application/json');
echo json_encode($response);
