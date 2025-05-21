import sys
import json
import re

def clean_text(text):
    return re.sub(r'[^\w\s]', '', text.lower())

def extract_skills(cv_text, job_skills):
    cv_text = clean_text(cv_text)
    matches = [skill for skill in job_skills if skill.lower() in cv_text]
    return len(matches), matches

if __name__ == "__main__":
    job_skills = json.loads(sys.argv[1])
    cv_text = sys.argv[2]

    match_count, matched_skills = extract_skills(cv_text, job_skills)
    total = len(job_skills)
    score = (match_count / total * 100) if total > 0 else 0

    print(round(score, 2))  # e.g., 87.5
