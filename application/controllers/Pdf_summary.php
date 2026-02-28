<?php

/**
 * @property CI_Input $input
 * @property CI_Upload $upload
 */
class Pdf_summary extends CI_Controller
{
	private const MAX_SUMMARY_SENTENCES = 5;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(['form', 'url']);
	}

	public function index()
	{
		$this->load->view('pdf_summary/form', [
			'error' => '',
			'summary' => '',
			'original_text' => '',
		]);
	}

	public function summarize()
	{
		$config = [
			'upload_path' => FCPATH . '../uploads/',
			'allowed_types' => 'pdf',
			'max_size' => 10240,
			'file_ext_tolower' => true,
		];

		if (! is_dir($config['upload_path'])) {
			mkdir($config['upload_path'], 0775, true);
		}

		$this->load->library('upload', $config);

		if (! $this->upload->do_upload('pdf_file')) {
			$this->load->view('pdf_summary/form', [
				'error' => strip_tags($this->upload->display_errors()),
				'summary' => '',
				'original_text' => '',
			]);
			return;
		}

		$uploadData = $this->upload->data();
		$filePath = $uploadData['full_path'];
		$originalText = $this->extractTextFromPdf($filePath);

		if ($originalText === '') {
			$this->load->view('pdf_summary/form', [
				'error' => 'Could not extract text from this PDF. Make sure pdftotext is installed and the PDF contains selectable text.',
				'summary' => '',
				'original_text' => '',
			]);
			@unlink($filePath);
			return;
		}

		$summary = $this->generateSummary($originalText, self::MAX_SUMMARY_SENTENCES);

		$this->load->view('pdf_summary/form', [
			'error' => '',
			'summary' => $summary,
			'original_text' => $originalText,
		]);

		@unlink($filePath);
	}

	private function extractTextFromPdf(string $pdfPath): string
	{
		$binary = trim((string) shell_exec('command -v pdftotext'));
		if ($binary === '') {
			return '';
		}

		$outputPath = tempnam(sys_get_temp_dir(), 'pdfsum_');
		if ($outputPath === false) {
			return '';
		}

		$command = sprintf('%s %s %s 2>&1', escapeshellcmd($binary), escapeshellarg($pdfPath), escapeshellarg($outputPath));
		shell_exec($command);
		$text = @file_get_contents($outputPath);
		@unlink($outputPath);

		if (! is_string($text)) {
			return '';
		}

		return trim(preg_replace('/\s+/', ' ', $text));
	}

	private function generateSummary(string $text, int $maxSentences): string
	{
		$sentences = preg_split('/(?<=[.?!])\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);
		if ($sentences === false || $sentences === []) {
			return '';
		}

		$wordFrequencies = [];
		foreach ($sentences as $sentence) {
			$words = preg_split('/[^a-zA-Z0-9]+/', strtolower($sentence), -1, PREG_SPLIT_NO_EMPTY);
			if ($words === false) {
				continue;
			}
			foreach ($words as $word) {
				if (strlen($word) < 3) {
					continue;
				}
				$wordFrequencies[$word] = ($wordFrequencies[$word] ?? 0) + 1;
			}
		}

		if ($wordFrequencies === []) {
			return implode(' ', array_slice($sentences, 0, $maxSentences));
		}

		$sentenceScores = [];
		foreach ($sentences as $index => $sentence) {
			$score = 0;
			$words = preg_split('/[^a-zA-Z0-9]+/', strtolower($sentence), -1, PREG_SPLIT_NO_EMPTY);
			if ($words === false) {
				continue;
			}
			foreach ($words as $word) {
				$score += $wordFrequencies[$word] ?? 0;
			}
			$sentenceScores[$index] = $score;
		}

		arsort($sentenceScores);
		$topIndexes = array_slice(array_keys($sentenceScores), 0, $maxSentences);
		sort($topIndexes);

		$summarySentences = [];
		foreach ($topIndexes as $index) {
			$summarySentences[] = $sentences[$index];
		}

		return implode(' ', $summarySentences);
	}
}
