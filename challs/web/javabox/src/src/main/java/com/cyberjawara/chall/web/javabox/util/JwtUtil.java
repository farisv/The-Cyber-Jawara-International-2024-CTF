package com.cyberjawara.chall.web.javabox.util;

import io.jsonwebtoken.Claims;
import io.jsonwebtoken.Jwts;
import io.jsonwebtoken.SignatureAlgorithm;

import java.util.Date;
import java.util.Map;

public class JwtUtil {
  private static final String SECRET_KEY = "c31bcd4ffcff8e971a6ad6ddcbdc613a1246f4223c00fa37404b501ad749257c";

  public static String generateToken(String username, boolean isAdmin) {
    return Jwts.builder()
      .setClaims(Map.of("username", username, "isAdmin", isAdmin))
      .setExpiration(new Date(System.currentTimeMillis() + 1000 * 60 * 60))
      .signWith(SignatureAlgorithm.HS256, SECRET_KEY)
      .compact();
  }

  public static Claims validateToken(String token) {
    return Jwts.parser()
      .setSigningKey(SECRET_KEY)
      .parseClaimsJws(token)
      .getBody();
  }
}
