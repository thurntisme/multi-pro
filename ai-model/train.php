<?php
require 'vendor/autoload.php';

use Phpml\Classification\NaiveBayes;
use Phpml\ModelManager;

// Prepare training data
$samples = [
  // Each entry represents a set of words (features)
  ['hello'],
  ['hi'],
  ['hey'],
  ['goodbye'],
  ['bye'],
  ['help'],
  ['support'],
];
$labels = [
  // Each label corresponds to the sample
  'greeting',
  'greeting',
  'greeting',
  'farewell',
  'farewell',
  'help',
  'help',
];

// Preprocess data by converting text to lowercase and splitting by spaces
function preprocess($text)
{
  // Convert to lowercase and split into words
  return explode(' ', strtolower($text));
}

// Process the samples by applying the preprocessing function
$processedSamples = array_map('preprocess', [
  'hello',
  'hi',
  'hey',
  'goodbye',
  'bye',
  'help',
  'support',
]);

// Train the model
$classifier = new NaiveBayes();
$classifier->train($processedSamples, $labels);

// Save the model to a file
$modelManager = new ModelManager();
$modelManager->saveToFile($classifier, __DIR__ . '/chatbot_model.model');

echo "Model trained and saved!";
